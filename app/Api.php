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
     * @param $type
     * @param $id
     * @return array
     */
    public function request($type, $id = false)
    {
        $client = new Client([
            'base_uri' => config('app.datastore_url')
        ]);

        $appendId = '';
        if ($id) {
            $appendId = '/' . $id;
        }

        try {
            $response = $client->request('GET', $type . $appendId);
            $status = $response->getStatusCode();
            if ($status == 200) {
                $data = json_decode($response->getBody(), true);
                if (!is_null($data)) {
                    if (isset($data['data'])) {
                        foreach ($data['data'] as $key => $item) {
                            if (isset($item['video_url'])) {
                                // Use AWS storage URL
                                $videoUrl = $item['video_url'] . '/url';
                                $contentUrl = $this->getPlaybackUrl($videoUrl);
                                $data['data'][$key]['video_url'] = $contentUrl;
                            } elseif ($key == 'video_url') {
                                $videoUrl = $data['data']['video_url'] . '/url';
                                $contentUrl = $this->getPlaybackUrl($videoUrl);
                                $data['data']['video_url'] = $contentUrl;
                            }
                        }
                    }

                    return [
                        'success' => true,
                        'data' => $data['data']
                    ];
                }
                return [
                    'success' => false,
                    'message' => 'Video asset not found.'
                ];
            }
        } catch (\Exception $e) {
            //@todo Implement more descriptive/friendly exception messages
            return [
                'success' => false,
                'message' => 'Video asset not found.'
            ];
        }
    }

    /**
     * @param $contentUrl
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getPlaybackUrl($contentUrl)
    {
        $client = new Client();
        $response = $client->request('GET', $contentUrl);
        return $response->getBody();
    }
}
