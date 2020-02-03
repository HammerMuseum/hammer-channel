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
    public function view(Request $request, $slug)
    {
        $data = $this->api->request('videos/' . $slug);
        if (isset($data['success']) && $data['success']) {
            return view('app', [
                'state' => $this->getAppState($data)
            ]);
        }
        return view('app', [
            'state' => $this->getAppState($data)
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewJson(Request $request, $id)
    {
        $data = $this->api->request('videos/' . $id);

        if (isset($data['success']) && $data['success']) {
            $state = $this->getAppState($data);
            return response()->json($state);
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function getAppState($data)
    {
        return [
            'path' => '/viewJson',
            'data' => isset($data['data'][0]) ? $data['data'][0] : [],
            'message' => isset($data['message']) ? $data['message'] : false,
        ];
    }
}
