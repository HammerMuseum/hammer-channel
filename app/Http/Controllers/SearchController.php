<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;
use App\Library\Facets;
use Illuminate\Support\Str;

/**
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /** @var Api */
    protected $api;

    protected $facetHandler;

    /**
     * SearchController constructor.
     * @param Api $api
     */
    public function __construct(
        Api $api,
        Facets $facetHandler
    ) {
        $this->api = $api;
        $this->facetHandler = $facetHandler;
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
            $facets = $this->facetHandler->getFacetOptions($results['data']['aggregations']);
            if ($results && !isset($results['error'])) {
                return view('result', [
                    'videos' => $results['data'],
                    'term' => $searchTerm,
                    'message' => false,
                    'title' => ucfirst($searchTerm),
                    'facets' => $facets
                ]);
            }
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'message' => 'No results found',
            'title' => '',
            'facets' => false
        ]);
    }

    /**
     * @param Request $request
     * @param $term
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter(Request $request, $term)
    {
        $queryParams = $request->all();
        if (!empty($queryParams)) {
            $results = $this->api->request('search/filter/' . $term . '?' . http_build_query($queryParams));
            $facets = $this->facetHandler->getFacetOptions($results['data']['aggregations']);
            if ($results['success'] && !isset($results['error'])) {
                return view('result', [
                    'videos' => $results['data'],
                    'term' => $term,
                    'message' => false,
                    'title' => ucfirst($term),
                    'facets' => $facets
                ]);
            }
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'message' => 'No results found',
            'title' => '',
            'facets' => false
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
        $orderValue = $request->get('order');

        if (!is_null($orderValue)) {
            $order = Str::before($orderValue, $field . '_');
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
