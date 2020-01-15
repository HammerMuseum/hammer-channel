<?php

namespace App\Library;

class Facets
{
    protected $facetMap = [
        'date' => 'Year Recorded'
    ];

    protected $facetOptions = [];

    public function getFacetOptions($aggregations)
    {
        foreach ($aggregations as $facet => $aggregation) {
            if (isset($this->facetMap[$facet])) {
                foreach ($aggregation['buckets'] as $bucket) {
                    if ($bucket['doc_count'] > 0) {
                        $this->facetOptions[$this->facetMap[$facet]][] = $bucket;
                    }
               }
            }
        }
        return $this->facetOptions;
    }
}
