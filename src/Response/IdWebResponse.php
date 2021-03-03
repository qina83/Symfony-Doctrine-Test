<?php


namespace App\Response;


use App\Repository\PaginationInfo;
use Symfony\Component\HttpFoundation\JsonResponse;

class IdWebResponse extends JsonResponse
{
    /**
     * PersonsWebResponse constructor.
     */
    public function __construct(string $id)
    {
        parent::__construct([
            'id' => $id
        ]);
    }
}