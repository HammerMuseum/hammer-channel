<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;

/**
 * Class VideoController
 * @package App\Http\Controllers
 */
class VideoController extends Controller
{
    /** @var Api */
    protected $api;

    /**
     * VideoController constructor.
     * @param Api $api
     */
    public function __construct(
        Api $api
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
        $data = $this->api->request('videos/' . $id);
        if (isset($data['success']) && $data['success']) {
            return view('video', [
                'data' => $data['data'][0],
                'message' => false
            ]);
        }
        return view('video', [
           'data' => false,
           'message' => $data['message']
        ]);
    }
}
