<?php


namespace App\Response;


use App\Repository\PaginationInfo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidSchemaWebResponse extends JsonResponse
{
    /**
     * PersonsWebResponse constructor.
     */
    public function __construct(array $errors)
    {
        parent::__construct([
            'message' => 'Invalid request',
            'errors' => $errors,
        ], Response::HTTP_BAD_REQUEST);
    }
}