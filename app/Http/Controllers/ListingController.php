<?php

namespace App\Http\Controllers;

use App\Api;
use App\Library\Pagination;
use Illuminate\Http\Request;

/**
 * Class ListingController
 * @package App\Http\Controllers
 */
class ListingController extends Controller
{
    /** @var Api */
    protected $api;

    /** @var Pagination */
    protected $pagination;

    /**
     * ListingController constructor.
     * @param Api $api
     * @param Pagination $pagination
     */
    public function __construct(
        Api $api,
        Pagination $pagination
    ) {
        $this->api = $api;
        $this->pagination = $pagination;
    }

    /**
     * Fetches all videos from API and returns the template listing.blade.php
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $videos = $this->api->request('term', http_build_query($params));

        return view('app', [
            'state' => $this->getAppState($videos, $params)
        ]);
    }

    /**
     * Return a JSON version of the index action
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexJson(Request $request)
    {
        $params = $request->all();
        $videos = $this->api->request('term', http_build_query($params));
        $state = $this->getAppState($videos, $params);
        return response()->json($state);
    }

    public function getAppState($data, $params)
    {
        $pagerLinks = [];
        if (!empty($data['pages'])) {
            $pagerLinks = $this->pagination->pagerLinks($data['pages']['pager']);
        }

        $topics = [];
        if (isset($data['aggregations']) && isset($data['aggregations']['topics'])) {
            foreach ($data['aggregations']['topics']['buckets'] as $topic) {
                $topics[$topic['key']] = $topic['by_topic']['hits']['hits'];
            }
        }
        return [
            'path' => '/',
            'videos' => isset($data['data']) ? $data['data'] : [],
            'pager' => $pagerLinks,
            'message' => isset($data['message']) ? $data['message'] : false,
            'title' => '',
            'show_clear' => false,
            'topics' => $topics,
            'clearedPageQuery' => $this->pagination->clearParams($params, ['start']),
            'clearedSortQuery' => $this->pagination->clearParams($params, ['sort', 'order']),
        ];
    }

    /**
     * List videos by topic
     *
     * @param Request $request
     * @param $keyword
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topic(Request $request, $keyword)
    {
        $queryString = http_build_query(['tags' => $keyword]);
        $result = $this->api->request('search', $queryString);
        if (isset($result['success']) && $result['success']) {
            $requestUrl = $request->url();
            $pagerLinks = [];
            if (!empty($videos['pages'])) {
                $pagerLinks = $this->pagination->pagerLinks($requestUrl, $videos['pages']['pager']);
            }
            return view('listing', [
                'videos' => $result['data'],
                'message' => false,
                'title' => ucfirst($keyword),
                'pagerLinks' => $pagerLinks,
                'show_clear' => false
            ]);
        }
        return view('listing', [
            'videos' => false,
            'message' => 'No videos available.',
            'title' => '',
            'pagerLinks' => [],
            'show_clear' => false,
        ]);
    }
}
