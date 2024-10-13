<?php

namespace  App\Http\Controllers\API\V1;

use App\DTOs\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Services\V1\UserService;
use App\Utils\ApiLogger;
use App\Utils\Helpers;
use App\Utils\ResponseDecorator;
use Exception;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private const REGISTRATION = 'USER_REGISTRATION';

    public function __construct(private UserService $userService) {}

    public function register(UserRegistrationRequest $request)
    {
        try {
             // Create a DTO from the validated request data
             $userDto = new RegisterUserDTO($request->validated());

             // Call the service class to register the user
             $user = $this->userService->registerUser($userDto);

            return ResponseDecorator::json([
                'status' => Response::HTTP_CREATED,
                'code' => Response::HTTP_CREATED,
                'message' => 'User successfully registered',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            $code = $exception->getCode();
            $reason = $exception->getMessage();
            Helpers::writeToLog(self::REGISTRATION, "error", $reason, [
                'reason' => $reason,
                'request_data' => $request->all()
            ]);
            ApiLogger::addOrUpdateInApiLog('error_message', $reason);

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
