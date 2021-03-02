<?php


use App\Repository\Page;
use Symfony\Component\HttpFoundation\Request;

class PageMapper
{
    const MAX_ITEMS_PER_PAGE = 3;

    public static function fromRequest(Request $request): Page{

        $pageSizePar = $request->query->get('pageSize');
        $pagePar = $request->query->get('page');

        $pageSize = max(1, intval($pageSizePar));
        $page = min(self::MAX_ITEMS_PER_PAGE, intval($pagePar));

        return new Page($pageSize, $page);
    }
}