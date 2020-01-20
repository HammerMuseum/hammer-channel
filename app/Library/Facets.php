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
}
