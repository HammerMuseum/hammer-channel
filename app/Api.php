<?php

namespace App;

use GuzzleHttp\Client;

/**
 * Class Api
 * @package App
 */
class Api
{
    /** @var string */
    protected $restUrl = '[POPULATE WITH REST URL]';

    /**
     * Request data from the API
     *
     * @param $type
     * @param $id
     * @return array
     */
    public function request($type, $id)
    {
        $client = new Client([
            'base_uri' => $this->restUrl
        ]);

        try {
            $response = $client->request('GET', $type . '/' . $id);
            $status = $response->getStatusCode();
            if ($status == 200) {
                $data = json_decode($response->getBody());
                return [
                  'success' => true,
                  'data' => $data
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
