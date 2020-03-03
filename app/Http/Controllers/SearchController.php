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
        $validParams = config('constants.validQueryParams');
        $params = $request->only($validParams);
        $queryString = $this->facetHandler->getFacetQueryString();
        $results = $this->api->request('search', $queryString);
        $state = $this->getAppState($results, $request, $params, $queryString);
        return view('app', [
            'state' => $state
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchJson(Request $request)
    {
        $validParams = config('constants.validQueryParams');
        $params = $request->only($validParams);
        $queryString = $this->facetHandler->getFacetQueryString();
        $results = $this->api->request('search', $queryString);
        $state = $this->getAppState($results, $request, $params, $_SERVER["QUERY_STRING"]);
        return response()->json($state);
    }

    /**
     * @param array $data
     * @param Request $request
     * @param array $params
     * @param string $originalQuery
     * @return array
     */
    public function getAppState($data, $request, $params, $originalQuery)
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
        $term = $request->get('term');
        return [
            'path' => '/search',
            'videos' => isset($data['data']) ? $data['data'] : [],
            'pager' => $pagerLinks,
            'term' => $term,
            'message' => false,
            'title' => !is_null($term) ? 'Results for "' . ucfirst($term) . '"' : '""',
            'facets' => $facets,
            'url' => $requestUrl,
            'currentQuery' => $originalQuery,
            'clearedPageQuery' => $this->pagination->clearParams($params, ['start']),
            'clearedSortQuery' => $this->pagination->clearParams($params, ['sort', 'order']),
            'show_clear' => true
        ];
    }
}
