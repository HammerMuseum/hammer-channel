<?php

namespace App\Http\Controllers;

use App\Api;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $videos = $this->api->request('videos', false);

        if (isset($videos['success']) && $videos['success']) {
            return view('listing', [
               'videos' => $videos['data'],
               'message' => false,
               'title' => 'All Videos',
               'show_clear' => true
            ]);
        }
        return view('listing', [
           'videos' => false,
           'message' => 'No videos available.',
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
        $result = $this->api->request('search', '?' . $queryString);
        if (isset($result['success']) && $result['success']) {
            return view('listing', [
                'videos' => $result['data'],
                'message' => false,
                'title' => ucfirst($keyword),
                'show_clear' => false
            ]);
        }
        return view('listing', [
            'videos' => false,
            'message' => 'No videos available.',
            'title' => '',
            'show_clear' => false
        ]);
    }
}
