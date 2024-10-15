<?php

namespace  App\Http\Controllers\API\V1;

use App\DTOs\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Services\V1\UserService;
use App\Utils\Helpers;
use App\Utils\ResponseDecorator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Register a new user based on the provided registration request.
     *
     * @param UserRegistrationRequest $request The request containing user registration data.
     * @return Response The JSON response indicating the status of the registration process.
     */
    public function register(UserRegistrationRequest $request)
    {
        try {
            // Create a DTO from the validated request data
            $userDto = new RegisterUserDTO($request->validated());

            // Call the service class to register the user
            $user = $this->userService->registerUser($userDto);

            return ResponseDecorator::json([
                'status' => Helpers::SUCCESS,
                'code' => Response::HTTP_CREATED,
                'message' => 'User successfully registered',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            $code = $exception->getCode();
            $reason = $exception->getMessage();

            return ResponseDecorator::json([
                'error' => [
                    'code' => $code,
                    'status' => Helpers::FAILED,
                    'reason' => $reason,
                    'message' => 'Something went wrong. Please try again or contact system administrator.',
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Search for a user based on the provided National ID (NID) in the request.
     *
     * @param Request $request The request object containing the NID to search for.
     * @return Response The JSON response indicating the search result and status.
     */
    public function search(Request $request)
    {
        try {
            $nid = $request->nid ?? null;

            // Call the service class to search the user
            $user = $nid ? $this->userService->searchUser($nid) : null;

            $message = $user ? "User found successfully" : "User not found";
            $code = $user ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
            return ResponseDecorator::json([
                'status' => Helpers::SUCCESS,
                'code' => Response::HTTP_OK,
                'message' => $message,
                'data' => $user ?? null
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            $code = $exception->getCode();
            $reason = $exception->getMessage();

            return ResponseDecorator::json([
                'error' => [
                    'code' => $code,
                    'status' => Helpers::FAILED,
                    'reason' => $reason,
                    'message' => 'Something went wrong. Please try again or contact system administrator.',
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
