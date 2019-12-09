<?php

namespace App;

use GuzzleHttp\Client;

class Api
{
    protected $restUrl = 'http://datastore.rufio.office.cogapp.com/api/';

    public function request($type, $id)
    {
        $client = new Client([
            'base_uri' => $this->restUrl
        ]);

        try {
            $response = $client->request('GET', $type . '/id/' . $id);
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