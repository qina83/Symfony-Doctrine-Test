<?php


namespace App\Service;


class PaginationInfo
{
private int $totalItems;
private int $totalPages;

    /**
     * PaginationInfo constructor.
     * @param int $totalItems
     * @param int $totalPages
     */
    public function __construct(int $totalItems, int $totalPages)
    {
        $this->totalItems = $totalItems;
        $this->totalPages = $totalPages;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}