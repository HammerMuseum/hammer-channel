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
        'date' => 'Year Recorded',
        'series' => 'Program Series',
        'speakers' => 'Speakers'
    ];

    /**
     * Build an array of available facet options for filtering
     *
     * @param $aggregations
     * @return array
     */
    public function getFacetOptions($aggregations)
    {
        $facetOptions = [];
        foreach ($aggregations as $facet => $aggregation) {
            if ($facet == 'label') {
                foreach ($aggregation as $facetLabel => $globalAggregation) {
                    if (isset($this->facetMap[$facetLabel])) {
                        foreach ($globalAggregation['buckets'] as $bucket) {
                            if (count($globalAggregation['buckets']) > 0) {
                                $facetOptions[$this->facetMap[$facetLabel]][] = $bucket;
                            }
                        }
                    }
                }
                return $facetOptions;
            }
            if (isset($this->facetMap[$facet])) {
                foreach ($aggregation['buckets'] as $bucket) {
                    if (count($aggregation['buckets']) > 0) {
                        $facetOptions[$this->facetMap[$facet]][] = $bucket;
                    }
                }
            }
        }
        return $facetOptions;
    }

    /**
     * Deconstruct URL parameters to build a query string for the API
     *
     * @param $params
     * @return string
     */
    public function getFacetQueryString($params)
    {
        $queryString = '';
        $facetQueryString = 'facets=';
        foreach ($params as $key => $value) {
            if ($key == 'facets') {
                // Pull out the [0] pattern
                $filters = preg_split("(\D[0-9]])  ", $value);
                foreach ($filters as $filter) {
                    if ($filter !== '') {
                        $facetQueryString .= $filter . ';';
                    }
                }

            } else {
                $queryString .= "&$key=$value";
            }
        }
        $facetQueryString .= $queryString;
        return $facetQueryString;
    }
}
