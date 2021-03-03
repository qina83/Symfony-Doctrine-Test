<?php


use App\Repository\Page;
use Symfony\Component\HttpFoundation\Request;

class PageMapper
{
    public static function fromRequest(Request $request): Page{

        $pageSizePar = $request->query->get('pageSize');
        $pagePar = $request->query->get('page');
        return new Page(intval($pageSizePar),  intval($pagePar));
    }

}