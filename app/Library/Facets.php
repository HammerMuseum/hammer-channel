<?php

namespace App\Library;

/**
 * Class Facets
 * @package App\Library
 */
class Facets
{
    /** @var array */
    protected $facetMap = [
        'date' => 'Year Recorded'
    ];

    /**
     * @param $aggregations
     * @return array
     */
    public function getFacetOptions($aggregations)
    {
        $facetOptions = [];
        foreach ($aggregations as $facet => $aggregation) {
            if (isset($this->facetMap[$facet])) {
                foreach ($aggregation['buckets'] as $bucket) {
                    if ($bucket['doc_count'] > 0) {
                        $facetOptions[$this->facetMap[$facet]][] = $bucket;
                    }
                }
            }
        }
        return $facetOptions;
    }

    /**
     * Deconstruct URL parameters to build a querystring for the API
     * @param $params
     * @return string
     */
    public function getFacetQueryString($params)
    {
        $queryString = '';
        $facetQueryString = 'facets=';
        foreach ($params as $key => $value) {
            if ($key == 'facets') {
                $query = explode(':', substr($value, 3));
                $query = $query[0] . ':' . $query[1] . ';';
                $facetQueryString .= $query;
            } else {
                $queryString .= "&$key=$value";
            }
        }
        $facetQueryString .= $queryString;
        return $facetQueryString;
    }
}
