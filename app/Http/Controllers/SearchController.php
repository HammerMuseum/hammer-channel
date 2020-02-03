<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;
use App\Library\Facets;
use App\Library\Pagination;
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

    /** @var Pagination */
    protected $pagination;

    /**
     * SearchController constructor.
     * @param Api $api
     * @param Facets $facetHandler
     * @param Pagination $pagination
     */
    public function __construct(
        Api $api,
        Facets $facetHandler,
        Pagination $pagination
    ) {
        $this->api = $api;
        $this->facetHandler = $facetHandler;
        $this->pagination = $pagination;
    }

    /**
     * Perform a search or sorted/filtered search
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $params = $request->all();
        $term = $request->get('term');
        if (!is_null($term)) {
            if (array_key_exists('facets', $params)) {
                $queryString = $this->facetHandler->getFacetQueryString($params);
                $results = $this->api->request('search/filter/' . $term, $queryString);
            } else {
                $results = $this->api->request('search/' . $term, http_build_query($params));
            }
            $state = $this->getAppState($results, $request, $params, $term);
            if ($results && !isset($results['error']) && isset($results['data'])) {
                return view('app/search', [
                    'state' => $state
                ]);
            }
            return view('app', [
                'state' => $state
            ]);
        }
        return view('app', [
            'state' => $this->getAppState([], $request, $params, $term)
        ]);
    }

    public function searchJson(Request $request)
    {
        $params = $request->all();
        $term = $request->get('term');
//        if (!is_null($term)) {
        if (array_key_exists('facets', $params)) {
            $queryString = $this->facetHandler->getFacetQueryString($params);
            $results = $this->api->request('search/filter/' . $term, $queryString);
        } else {
            $results = $this->api->request('search/' . $term, http_build_query($params));
        }
        $state = $this->getAppState($results, $request, $params, $term);
        return response()->json($state);
//        }
    }

    /**
     * @param array $data
     * @param Request $request
     * @param array $params
     * @param string $term
     * @return array
     */
    public function getAppState($data, $request, $params, $term)
    {
        $requestUrl = $request->url();
        $pagerLinks = [];
        if (!empty($data['pages'])) {
            $pagerLinks = $this->pagination->pagerLinks($data['pages']['pager'], $params);
        }
        $facets = [];
        if (isset($data['aggregations'])) {
            $facets = $this->facetHandler->getFacetOptions($data['aggregations']);
        }
        return [
            'path' => 'searchJson',
            'videos' => isset($data['data']) ? $data['data'] : [],
            'pager' => $pagerLinks,
            'term' => $term,
            'message' => false,
            'title' => 'Results for "' . ucfirst($term) . '"',
            'facets' => $facets,
            'url' => $requestUrl,
            'query' => $request->fullUrl(),
            'clearedPageQuery' => '?' . $this->pagination->clearParams($params, ['start']),
            'clearedSortQuery' => '?' . $this->pagination->clearParams($params, ['sort', 'order']),
            'show_clear' => true
        ];
    }
}
