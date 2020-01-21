<?php

namespace App\Http\Controllers;

use App\Api;
use Illuminate\Http\Request;

/**
 * Class ListingController
 * @package App\Http\Controllers
 */
class ListingController extends Controller
{
    /** @var Api */
    protected $api;

    /**
     * ListingController constructor.
     * @param Api $api
     */
    public function __construct(
        Api $api
    ) {
        $this->api = $api;
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
        $videos = $this->api->request('videos', false, '?' . http_build_query($params));
        $requestUrl = $request->url();

        if (isset($videos['success']) && $videos['success']) {
            $prevLink = $videos['data']['_links']['prev'] !== ''?
                rtrim($requestUrl, '/\\') . $videos['data']['_links']['prev'] : false;
            $nextLink = $videos['data']['_links']['next'] !== '' ?
                rtrim($requestUrl, '/\\') . $videos['data']['_links']['next'] : false;
            return view('listing', [
               'videos' => $videos['data'],
               'nextLink' => $nextLink,
               'prevLink' => $prevLink,
               'message' => false,
               'title' => '',
               'show_clear' => true
            ]);
        }
        return view('listing', [
           'videos' => false,
           'message' => 'No videos available.',
           'pages' => false,
           'nextLink' => false,
           'prevLink' => false,
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
        $result = $this->api->request('search', false, '?' . $queryString);
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
