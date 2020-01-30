<?php

namespace App;

use GuzzleHttp\Client;

/**
 * Class Api
 * @package App
 */
class Api
{
    /**
     * Request data from the API
     *
     * @param string $type
     * @param string $queryString
     * @return array
     *
     */
    public function request($type, $queryString = '')
    {
        $client = new Client([
            'base_uri' => config('app.datastore_url')
        ]);

        try {
            $response = $client->request('GET', $type . '?' . $queryString);
            $status = $response->getStatusCode();
            if ($status == 200) {
                $data = json_decode($response->getBody(), true);
                if (!is_null($data)) {
                    if (isset($data['data']) && !empty($data['data'])) {
                        foreach ($data['data'] as $key => $item) {
                            if (isset($item['video_url'])) {
                                $videoUrl = $item['video_url'] . '/url';
                                $contentUrl = $this->getPlaybackUrl($videoUrl);
                                $data['data'][$key]['video_url'] = $contentUrl;
                            }
                        }
                    }
                    return [
                        'success' => true,
                        'data' => $data['data'],
                        'pages' => $data['pages'],
                        'aggregations' => $data['aggregations']
                    ];
                }
                return [
                    //@todo Implement more descriptive/friendly error messages
                    'success' => false,
                    'message' => isset($data['message']) ? $data['message'] : 'An error occurred',
                    'error' => true,
                    'pages' => [],
                    'data' => [],
                    'aggregations' => []
                ];
            }
        } catch (\Exception $e) {
            //@todo Implement more descriptive/friendly error messages
            return [
                'success' => false,
                'message' => 'An error occurred.',
                'error' => true,
                'data' => [],
                'pages' => [],
                'aggregations' => []
            ];
        }
    }

    /**
     * @param string $contentUrl
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getPlaybackUrl($contentUrl)
    {
        $client = new Client();
        $response = $client->request('GET', $contentUrl);
        return $response->getBody()->getContents();
    }
}
