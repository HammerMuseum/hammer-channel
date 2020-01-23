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
     * @param array $pager
     * @param array $params
     * @return mixed
     */
    public function pagerLinks($pager, $params = [])
    {
        foreach ($pager as $key => $pagerLink) {
            if ($pagerLink !== '') {
                $pager[$key] = empty($params) ? '?' . $pagerLink : '&' . $pagerLink;
            } else {
                $pager[$key] = false;
            }
        }
        return $pager;
    }
}
