<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\V1\VaccineCenterService;
use App\Utils\ResponseDecorator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VaccineCenterController extends Controller
{
    public function __construct(private VaccineCenterService $service) {}

    public function index(Request $request)
    {
        try {
            $search = $request->get('search', null);
            $page = (int)$request->get('page', '1');
            $limit = (int)$request->get('limit', '50');
            $uriQueries = ['limit' => $limit];

            $paginatedData = $this->service->getVaccineCenters($limit, $search);
            $paginatedData->appends($uriQueries);
            
            return ResponseDecorator::json([
                'status' => Response::HTTP_OK,
                'code' => Response::HTTP_OK,
                'message' => 'Vaccine centers successfully retrieved',
                'data' => $paginatedData->items(),
                'links' => [
                    'prev' => $paginatedData->previousPageUrl(),
                    'self' => $paginatedData->url($page),
                    'next' => $paginatedData->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => (int) $paginatedData->currentPage(),
                    'per_page' => (int) $paginatedData->perPage(),
                    'total_rows' => (int) $paginatedData->total(),
                ],
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            $code = $exception->getCode();
            $reason = $exception->getMessage();

            return ResponseDecorator::json([
                'error' => [
                    'code' => $code,
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'reason' => $reason,
                    'message' => 'Something went wrong. Please try again or contact system administrator.',
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
