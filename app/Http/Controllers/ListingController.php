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
        $videos = $this->api->request('videos', http_build_query($params));

        if (isset($videos['success']) && $videos['success']) {
            $pagerLinks = [];
            if (!empty($videos['pages'])) {
                $pagerLinks = $this->pagination->pagerLinks($videos['pages']['pager']);
            }

            return view('app/home', [
               'videos' => $videos['data'],
               'pagerLinks' => $pagerLinks,
               'message' => false,
               'title' => '',
               'show_clear' => true
            ]);
        }
        return view('app/home', [
            'videos' => false,
            'pagerLinks' => [],
            'message' => 'No videos available.',
            'title' => '',
            'show_clear' => false
        ]);
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
