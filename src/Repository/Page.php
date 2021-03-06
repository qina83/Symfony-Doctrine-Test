<?php


namespace App\Repository;


class Page
{
    const MAX_ITEMS_PER_PAGE = 3;
    private int $size;
    private int $index;

    /**
     * Page constructor.
     * @param int $size
     * @param int $index
     */
    public function __construct(int $size, int $index)
    {
        $this->size = max(1, $size);
        $this->index = min(self::MAX_ITEMS_PER_PAGE, $index);
    }


    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    public function getOffset():int{
        return $this->size * ($this->index - 1);
    }


}