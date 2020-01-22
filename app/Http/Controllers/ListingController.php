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
        $requestUrl = $request->url();

        if (isset($videos['success']) && $videos['success']) {
            $pagerLinks = [];
            if (!empty($videos['pages'])) {
                $pagerLinks = $this->pagination->pagerLinks($requestUrl, $videos['pages']['pager']);
            }

            return view('listing', [
               'videos' => $videos['data'],
               'pagerLinks' => $pagerLinks,
               'message' => false,
               'title' => '',
               'show_clear' => true
            ]);
        }
        return view('listing', [
           'videos' => false,
           'message' => 'No videos available.',
           'pages' => false,
           'pagerLinks' => [],
           'title' => '',
           'show_clear' => false
        ]);
    }

    /**
     * List videos by topic
     *
     * @param $keyword
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topic($keyword)
    {
        $queryString = http_build_query(['tags' => $keyword]);
        $result = $this->api->request('search', $queryString);
        if (isset($result['success']) && $result['success']) {
            return view('listing', [
                'videos' => $result['data'],
                'message' => false,
                'title' => ucfirst($keyword),
                'nextLink' => false,
                'prevLink' => false,
                'show_clear' => false
            ]);
        }
        return view('listing', [
            'videos' => false,
            'message' => 'No videos available.',
            'title' => '',
            'nextLink' => false,
            'prevLink' => false,
            'show_clear' => false,
        ]);
    }
}
