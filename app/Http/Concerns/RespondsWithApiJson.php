<?php

namespace App\Http\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;

trait RespondsWithApiJson
{
    protected function apiSuccess(mixed $data = null, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function apiError(string $message, int $status = 400, mixed $errors = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    /**
     * @param  class-string<JsonResource>  $resourceClass
     */
    protected function apiPaginated(AbstractPaginator $paginator, string $resourceClass, string $message = ''): JsonResponse
    {
        $resolved = $resourceClass::collection($paginator)->response()->getData(true);

        return $this->apiSuccess([
            'items' => $resolved['data'] ?? [],
            'meta' => $resolved['meta'] ?? null,
        ], $message);
    }

    protected function apiResource(JsonResource $resource, string $message = '', int $status = 200): JsonResponse
    {
        return $this->apiSuccess($resource->resolve(), $message, $status);
    }
}
