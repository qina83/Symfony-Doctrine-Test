<?php


use App\Repository\PaginationInfo;

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