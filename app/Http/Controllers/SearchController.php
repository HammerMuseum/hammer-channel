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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('term');
        if (!is_null($searchTerm)) {
            $results = $this->api->request('search', $searchTerm);

            if ($results && !isset($results['error'])) {
                return view('result', [
                    'videos' => $results['data'],
                    'term' => $searchTerm,
                    'message' => false,
                    'title' => ucfirst($searchTerm)
                ]);
            }
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'message' => 'No results found',
            'title' => ''
        ]);
    }

    /**
     * @param Request $request
     *  The request from the form submission
     *
     * @param $term string
     *  The original search term
     *
     * @param $field string
     *  The field to sort by
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sort(Request $request, $term, $field)
    {
        $order = $request->get('order');

        if (!is_null($order)) {
            $results = $this->api->request('search', $term . '/' . $field . '/' . $order);
            if ($results && !isset($results['error'])) {
                return view('result', [
                    'videos' => $results['data'],
                    'term' => $term,
                    'message' => false,
                    'title' => ucfirst($term)
                ]);
            }
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'message' => 'No results found',
            'title' => ''
        ]);
    }
}
