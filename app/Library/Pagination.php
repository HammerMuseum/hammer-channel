<?php

namespace App\Library;

use Illuminate\Support\Arr;

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
                // If there are already query parameters, use the correct concatenation
                $pager[$key] = empty($params) ? $pagerLink : '&' . $pagerLink;
            } else {
                $pager[$key] = '';
            }
        }
        return $pager;
    }

    /**
     * Clear unwanted sorting parameters
     *
     * @param array $params
     * @param array $keys
     * @return mixed
     */
    public function clearParams($params, $keys = [])
    {
        return http_build_query(Arr::except($params, $keys));
    }
}
