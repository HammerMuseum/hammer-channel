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
            'base_uri' => env('DATASTORE_URL')
        ]);

        $appendId = '';
        if ($id) {
            $appendId = '/' . $id;
        }

        try {
            $response = $client->request('GET', $type . $appendId);
            $status = $response->getStatusCode();
            if ($status == 200) {
                $data = json_decode($response->getBody());
                if (!is_null($data)) {
                    return [
                        'success' => true,
                        'data' => $data
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
}
