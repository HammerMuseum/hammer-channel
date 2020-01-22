<?php

namespace App\Library;

/**
 * Class Pagination
 * @package App\Library
 */
class Pagination
{
    /**
     * Constructs pager links
     *
     * @param $requestUrl string
     * @param $pager array
     * @return mixed
     */
    public function pagerLinks($requestUrl, $pager)
    {
        foreach ($pager as $key => $pagerLink) {
            if ($pagerLink !== '') {
                $pager[$key] = rtrim($requestUrl, '/\\') . $pagerLink;
            } else {
                $pager[$key] = false;
            }
        }
        return $pager;
    }
}
