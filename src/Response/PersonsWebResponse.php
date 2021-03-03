<?php


namespace App\Response;


use App\Repository\PaginationInfo;
use PaginationInfoMapper;
use PersonMapper;
use Symfony\Component\HttpFoundation\JsonResponse;

class PersonsWebResponse extends JsonResponse
{

    /**
     * PersonsWebResponse constructor.
     */
    public function __construct(array $persons, PaginationInfo $paginationInfo)
    {
        $personsDto = [];
        foreach ($persons as $person) {
            $personsDto[] = PersonMapper::PersonToDto($person);
        }
        $paginationInfoDto = PaginationInfoMapper::PaginationInfoToDto($paginationInfo);
        parent::__construct([
            'items' => $personsDto,
            'paginationInfo' => PaginationInfoMapper::PaginationInfoToDto($paginationInfo)
        ]);
    }
}