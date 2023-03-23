<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;
use App\Library\Pagination;

class WatchLaterController extends Controller
{
    /** @var Api */
    protected $api;

    /** @var Pagination */
    protected $pagination;

    /**
     * WatchLaterController constructor.
     * @param Api $api
     * @param Pagination $pagination
     */
    public function __construct(
        Api $api,
        Pagination $pagination,
    ) {
        $this->api = $api;
        $this->pagination = $pagination;
    }

    public function index(Request $request)
    {
        // Retrieve the list of Ids from the cookie
        $ids = $request->cookie('saved_video_ids');

        // Convert the string of Ids into an array
        $idsArray = explode('|', $ids);

        // Remove empty values
        $idsArray = array_filter($idsArray);

        // Make API requests for each Id
        $videos = [];

        if ($ids && count($idsArray)) {
            foreach ($idsArray as $id) {
                $data = $this->api->request('videos/' . $id);

                if (!empty($data['data'])) {
                    $videos[] = $data['data'][0];
                }
            }
        }

        $state = $this->getAppState($videos, $request);

        return view('watch-later', [
            'state' => $state,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexJson(Request $request)
    {
        // Retrieve the list of Ids from the cookie
        $ids = $request->cookie('saved_video_ids');

        // Convert the string of Ids into an array
        $idsArray = explode('|', $ids);

        // Remove empty values
        $idsArray = array_filter($idsArray);

        // Make API requests for each Id
        $videos = [];

        if ($ids && count($idsArray)) {
            foreach ($idsArray as $id) {
                $data = $this->api->request('videos/' . $id);

                if (!empty($data['data'])) {
                    $videos[] = $data['data'][0];
                }
            }
        }

        $state = $this->getAppState($videos, $request);
        return response()->json($state);
    }

    /**
     * @param array $data
     * @param Request $request
     * @param array $params
     * @return array
     */
    public function getAppState($videos, $request)
    {
        $requestUrl = $request->url();

        return [
            'path' => '/search',
            'videos' => $videos,
            'message' => false,
            'title' => 'Watch later',
            'url' => $requestUrl,
            'show_clear' => true,
            'totals' => [
                'total' => count($videos),
            ],
        ];
    }
}
