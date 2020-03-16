<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;
use App\Library\Metadata;

/**
 * Class VideoController
 * @package App\Http\Controllers
 */
class VideoController extends Controller
{
    /** @var Api */
    protected $api;

    protected $metadata;

    /**
     * VideoController constructor.
     * @param Api $api
     * @param Metadata $metadata
     */
    public function __construct(
        Api $api,
        Metadata $metadata
    ) {
        $this->api = $api;
        $this->metadata = $metadata;
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
        return view('app', [
            'state' => $this->getAppState('/video/' . $slug, $data),
            'metadata' => $this->getMetadata($data)
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewJson(Request $request, $slug)
    {
        $data = $this->api->request('videos/' . $slug);
        $state = $this->getAppState('/video/' . $slug, $data);
        return response()->json($state);
    }

    /**
     * @param $data
     * @return array
     */
    public function getAppState($path, $data)
    {
        $flatData = [];
        $flatData['path'] = $path;
        foreach ($data['data'][0] as $k => $v) {
            $flatData[$k] = $v;
        }
        return $flatData;
    }

    /**
     * @param $data
     * @return array
     */
    public function getMetadata($data)
    {
        return $this->metadata->getMetadata($data['data'][0]);
    }
}
