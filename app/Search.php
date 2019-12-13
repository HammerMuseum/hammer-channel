<?php

namespace App;

use Elasticsearch\ClientBuilder;

/**
 * Class Search
 * @package App
 */
class Search
{
    /**
     * @param $term
     * @return array|bool
     */
    public function search($term)
    {
        $client = $this->initElasticSearch();
        $params = [
            'index' => env('ES_INDEX'),
            'body'  => [
                'query' => [
                    'match' => [
                        'title' => $term
                    ]
                ]
            ]
        ];
        $result = $client->search($params);
        var_dump($result);
        $response = [];
        if (isset($result['hits']['total']) && $result['hits']['total'] > 0) {
            foreach ($result['hits']['hits'] as $hit) {
                if (isset($hit['_source'])) {
                    $response[] = $hit['_source'];
                }
            }
            return $response;
        }
        return false;
    }

    /**
     * @return \Elasticsearch\Client
     */
    public function initElasticSearch()
    {
        $hosts = [
            env('ES_ENDPOINT')
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();

        return $client;
    }
}