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
               'title' => 'All Videos'
            ]);
        }
        return view('listing', [
           'videos' => false,
           'message' => 'No videos available.',
           'title' => ''
        ]);
    }
}
