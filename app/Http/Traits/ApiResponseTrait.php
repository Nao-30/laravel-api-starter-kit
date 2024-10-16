<?php
/**
 * Created by PhpStorm.
 * User: Bawa, Lakhveer
 * Email: iamdeep.dhaliwal@gmail.com
 * Date: 2020-06-14
 * Time: 12:18 p.m.
 */

namespace App\Http\Traits;

use App\Http\Resources\Empty\EmptyResource;
use App\Http\Resources\Empty\EmptyResourceCollection;
use Error;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

trait ApiResponseTrait
{
    /**
     * @param  null  $message
     * @param  int  $statusCode
     * @param  array  $headers
     * @return JsonResponse
     */
    protected function respondWithToken($token, $message = null, $statusCode = 200, $headers = [])
    {
        // https://laracasts.com/discuss/channels/laravel/pagination-data-missing-from-api-resource

        return $this->apiResponse(
            [
                'success' => true,
                'result' => [
                    'token' => $token,
                    '_type' => '_$FcmToken'
                ],
                'message' => $message,
            ],
            $statusCode,
            $headers
        );
    }

    /**
     * @param  null  $message
     * @param  int  $statusCode
     * @param  array  $headers
     * @return JsonResponse
     */
    protected function respondWithResource(JsonResource $resource, $message = null, $statusCode = 200, $headers = [])
    {
        // https://laracasts.com/discuss/channels/laravel/pagination-data-missing-from-api-resource

        return $this->apiResponse(
            [
                'success' => true,
                'result' => $resource,
                'message' => $message,
            ],
            $statusCode,
            $headers
        );
    }


    /**
     * @param  array  $data
     * @param  int  $statusCode
     * @param  array  $headers
     * @return array
     */
    public function parseGivenData($data = [], $statusCode = 200, $headers = [])
    {
        $responseStructure = [
            'success' => $data['success'],
            'message' => $data['message'] ?? '',
            'result' => $data['result'] ?? null,
        ];
        if (isset($data['last_page_url'])) {
            $responseStructure['first_page_url'] = $data['first_page_url'];
            $responseStructure['last_page_url'] = $data['last_page_url'];
            $responseStructure['next_page_url'] = $data['next_page_url'];
            $responseStructure['prev_page_url'] = $data['prev_page_url'];
            $responseStructure['per_page'] = $data['per_page'];
            $responseStructure['total'] = $data['total'];
            $responseStructure['current_page'] = $data['current_page'];
            $responseStructure['last_page'] = $data['last_page'];
        }
        if (isset($data['errors'])) {
            $responseStructure['errors'] = $data['errors'];
        }
        if (isset($data['status'])) {
            $statusCode = $data['status'];
        }

        if (isset($data['exception']) && ($data['exception'] instanceof Error || $data['exception'] instanceof Exception)) {
            if ('production' !== config('app.env')) {
                $responseStructure['exception'] = [
                    'message' => $data['exception']->getMessage(),
                    'file' => $data['exception']->getFile(),
                    'line' => $data['exception']->getLine(),
                    'code' => $data['exception']->getCode(),
                    'trace' => $data['exception']->getTrace(),
                ];
            }

            if (200 === $statusCode) {
                $statusCode = 500;
            }
        }
        if (false === $data['success']) {
            if (isset($data['error_code'])) {
                $responseStructure['error_code'] = $data['error_code'];
            }
        }

        return ['content' => $responseStructure, 'statusCode' => $statusCode, 'headers' => $headers];
    }

    /*
     *
     * Just a wrapper to facilitate abstract
     */

    /**
     * Return generic json response with the given data.
     *
     * @param  int  $statusCode
     * @param  array  $headers
     * @return JsonResponse
     */
    protected function apiResponse($data = [], $statusCode = 200, $headers = [])
    {
        // https://laracasts.com/discuss/channels/laravel/pagination-data-missing-from-api-resource

        $result = $this->parseGivenData($data, $statusCode, $headers);

        return response()->json(
            $result['content'],
            $result['statusCode'],
            $result['headers']
        );
    }

    /*
     *
     * Just a wrapper to facilitate abstract
     */

    /**
     * @param  null  $message
     * @param  int  $statusCode
     * @param  array  $headers
     * @return JsonResponse
     */
    protected function respondWithCollection(ResourceCollection $resourceCollection, $message = null, $statusCode = 200, $headers = [])
    {

        return $this->apiResponse(
            [
                'success' => true,
                'message' => $message,
                'result' => $resourceCollection->response()->getData(),

            ],
            $statusCode,
            $headers
        );
    }

    /*
     *
     * Just a wrapper to facilitate abstract
     */

    /**
     * @param  null  $message
     * @param  int  $statusCode
     * @param  array  $headers
     * @return JsonResponse
     */
    protected function respondWithResourceCollection(ResourceCollection $resourceCollection, LengthAwarePaginator $pagination, $message = null, $statusCode = 200, $headers = [])
    {

        // https://laracasts.com/discuss/channels/laravel/pagination-data-missing-from-api-resource
        return $this->apiResponse(
            [
                'success' => true,
                'message' => $message,
                'result' => $resourceCollection->response()->getData(),
                'first_page_url' => $pagination->url(1),
                'last_page_url' => $pagination->url($pagination->lastPage()),
                'next_page_url' => $pagination->nextPageUrl(),
                'prev_page_url' => $pagination->previousPageUrl(),
                'current_page' => $pagination->currentPage(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
                'total' => $pagination->total(),

            ],
            $statusCode,
            $headers
        );
    }

    /**
     * Respond with success.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondSuccess($message = '')
    {
        return $this->apiResponse(['success' => true, 'message' => $message, 'result' => ['_type' => "_\$Empty"]]);
    }

    /**
     * Respond with created.
     *
     *
     * @return JsonResponse
     */
    protected function respondCreated($data)
    {

        $arr = [
            'success' => true,
            'result' => $data
        ];
        return $this->apiResponse($arr, 201);
    }

    /**
     * Respond with no content.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondNoContent($message = 'No Content Found')
    {
        return $this->apiResponse(['success' => false, 'message' => $message], 200);
    }

    /**
     * Respond with no content.
     *
     *
     *
     * @return JsonResponse
     */
    protected function respondNoContentStatus()
    {
        return response()->noContent();
    }

    /**
     * Respond with no content.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondNoContentResource($message = 'No Content Found')
    {
        return $this->respondWithResource(new EmptyResource([]), $message);
    }

    /**
     * Respond with no content.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondNoContentResourceCollection($message = 'No Content Found')
    {
        return $this->respondWithResourceCollection(new EmptyResourceCollection([]), new LengthAwarePaginator(null, 0, 0), $message);
    }

    /**
     * Respond with unauthorized.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondUnAuthorized($message = 'العملية غير مصرح بها')
    {
        return $this->respondError($message, 403);
    }

    /**
     * Respond with error.
     *
     *
     * @param  bool|null  $error_code
     * @return JsonResponse
     */
    protected function respondError($message, int $statusCode = 400, Exception $exception = null, int $error_code = 1)
    {

        return $this->apiResponse(
            [
                'success' => false,
                'message' => $message ?? 'There was an internal error, Pls try again later',
                'exception' => $exception,
            ],
            $statusCode
        );
    }

    /**
     * Respond with forbidden.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->respondError($message, 403);
    }

    /**
     * Respond with not found.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondNotFound($message = 'الصفحة غير موجودة')
    {
        return $this->respondError($message, 404);
    }

    // /**
    //  * Respond with failed login.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // protected function respondFailedLogin()
    // {
    //     return $this->apiResponse([
    //         'errors' => [
    //             'email or password' => 'is invalid',
    //         ]
    //     ], 422);
    // }

    /**
     * Respond with internal error.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondInternalError($message = 'Internal Error')
    {
        return $this->respondError($message, 500);
    }

    protected function respondValidationErrors(ValidationException $exception)
    {
        return $this->apiResponse(
            [
                'success' => false,
                'message' => $exception->getMessage(),
                'result' => $exception->errors(),
            ],
            422
        );
    }
}
