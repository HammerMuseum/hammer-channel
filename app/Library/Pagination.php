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
                // If there are already query parameters, use the correct concatenation
                $pager[$key] = empty($params) ? '?' . $pagerLink : '&' . $pagerLink;
            } else {
                $pager[$key] = '';
            }
        }
        return $pager;
    }

    /**
     * Clear unwanted sorting parameters
     *
     * @param $params
     * @return mixed
     */
    public function clearParams($params, $keys = [])
    {
        foreach ($keys as $key) {
            if (isset($params[$key])) {
                unset($params[$key]);
            }
        }
        return http_build_query($params);
    }
}
