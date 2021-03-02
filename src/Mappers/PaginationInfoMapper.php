<?php


use App\Service\PaginationInfo;

class PaginationInfoMapper
{
    public static function PaginationInfoToDto(PaginationInfo $paginationInfo): array
    {
        return [
            'totalItems' => $paginationInfo->getTotalItems(),
            'totalPages' => $paginationInfo->getTotalPages(),
        ];
    }
}