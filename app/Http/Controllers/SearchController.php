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

    /** @var Facets */
    protected $facetHandler;

    /**
     * SearchController constructor.
     * @param Api $api
     * @param Facets $facetHandler
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
        $term = $request->get('term');
        $params = $request->all();
        if (!is_null($term)) {
            $results = $this->api->request('search', $term, '?' . http_build_query($params));
            $requestUrl = $request->url();
            if ($results && !isset($results['error'])) {
                // Construct next and previous links with the original searched term
                $prevLink = $results['data']['_links']['prev'] !== '' ?
                    rtrim($requestUrl, '/\\') . $results['data']['_links']['prev'] . '&term=' . $term : false;
                $nextLink = $results['data']['_links']['next'] !== '' ?
                    rtrim($requestUrl, '/\\') . $results['data']['_links']['next'] . '&term=' . $term : false;
                $facets = $this->facetHandler->getFacetOptions($results['data']['aggregations']);
                return view('result', [
                    'videos' => $results['data'],
                    'nextLink' => $nextLink,
                    'prevLink' => $prevLink,
                    'term' => $term,
                    'message' => false,
                    'title' => 'Results for "' . ucfirst($term) . '"',
                    'facets' => $facets,
                    'show_clear' => true,
                ]);
            }
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'nextLink' => false,
            'prevLink' => false,
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
            $results = $this->api->request('search/filter', $term, '?' . http_build_query($queryParams));
            $requestUrl = $request->url();
            $prevLink = $results['data']['_links']['prev'] !== '' ?
                rtrim($requestUrl, '/\\') . $results['data']['_links']['prev'] . '&term=' . $term : false;
            $nextLink = $results['data']['_links']['next'] !== '' ?
                rtrim($requestUrl, '/\\') . $results['data']['_links']['next'] . '&term=' . $term : false;
            $facets = $this->facetHandler->getFacetOptions($results['data']['aggregations']);
            if ($results['success'] && !isset($results['error'])) {
                return view('result', [
                    'videos' => $results['data'],
                    'nextLink' => $nextLink,
                    'prevLink' => $prevLink,
                    'term' => $term,
                    'message' => false,
                    'title' => 'Results for "' . ucfirst($term) . '"',
                    'facets' => $facets
                ]);
            }
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'nextLink' => false,
            'prevLink' => false,
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
            $order = Str::after($orderValue, $field . '_');
            $queryParams = http_build_query([
               'sort' => $field,
               'direction' => $order
            ]);
            $results = $this->api->request('search', $term, '?' . $queryParams);
            $requestUrl = $request->url();
            $prevLink = $results['data']['_links']['prev'] !== '' ?
                rtrim($requestUrl, '/\\') . $results['data']['_links']['prev'] . '&term=' . $term : false;
            $nextLink = $results['data']['_links']['next'] !== '' ?
                rtrim($requestUrl, '/\\') . $results['data']['_links']['next'] . '&term=' . $term : false;
            $facets = $this->facetHandler->getFacetOptions($results['data']['aggregations']);
            if ($results && !isset($results['error'])) {
                return view('result', [
                    'videos' => $results['data'],
                    'term' => $term,
                    'nextLink' => $nextLink,
                    'prevLink' => $prevLink,
                    'message' => false,
                    'title' => ucfirst($term),
                    'facets' => $facets
                ]);
            }
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'nextLink' => false,
            'prevLink' => false,
            'message' => 'No results found',
            'title' => '',
            'facets' => false
        ]);
    }
}
