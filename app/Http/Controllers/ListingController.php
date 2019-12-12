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
     * Return all videos from API
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $videos = $this->api->request('videos', false);

        if (isset($videos['success']) && $videos['success']) {
            return view('listing', [
               'videos' => $videos['data']->data,
               'message' => false
            ]);
        }
        return view('listing', [
           'videos' => false,
           'message' => 'No videos available.'
        ]);
    }
}
