<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;

/**
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /** @var Api */
    protected $api;

    /**
     * SearchController constructor.
     * @param Api $api
     */
    public function __construct(
        Api $api
    ) {
        $this->api = $api;
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('term');
        if (!is_null($searchTerm)) {
            $result = $this->api->request('search', $searchTerm);

            if (isset($result['data'])) {
                return view('listing', [
                    'videos' => $result['data'],
                    'message' => false,
                    'title' => ucfirst($searchTerm)
                ]);
            }
        }
        return view('listing', [
            'videos' => false,
            'message' => 'No results found',
            'title' => ''
        ]);
    }
}
