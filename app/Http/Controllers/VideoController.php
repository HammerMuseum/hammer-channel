<?php

namespace App\Http\Controllers;

use App\ApiInterface;
use Illuminate\Http\Request;

/**
 * Class VideoController
 * @package App\Http\Controllers
 */
class VideoController extends Controller
{
    protected $api;

    public function __construct(
        ApiInterface $api
    ) {
        $this->api = $api;
    }
    /**
     * View an individual video by calling the API by ID
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request, $id)
    {
        $data = $this->api->request('videos', $id);

        if (isset($data['success']) && $data['success']) {
            return view('video', [
                'data' => $data['data'],
                'message' => false
            ]);
        }
        return view('video', [
           'data' => false,
           'message' => $data['message']
        ]);
    }
}
