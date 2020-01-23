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
        $requestUrl = $request->url();
        if (!is_null($term)) {
            if (array_key_exists('facets', $params)) {
                $queryString = $this->facetHandler->getFacetQueryString($params);
                $results = $this->api->request('search/filter/' . $term, $queryString);
            } else {
                $results = $this->api->request('search/' . $term, http_build_query($params));
            }
            $clearedParams = $this->clearParams($params);
            if ($results && !isset($results['error']) && isset($results['data'])) {
                $pagerLinks = [];
                if (!empty($results['pages'])) {
                    $pagerLinks = $this->pagination->pagerLinks($results['pages']['pager'], $params);
                }
                $facets = $this->facetHandler->getFacetOptions($results['aggregations']);
                return view('result', [
                    'videos' => $results['data'],
                    'pagerLinks' => $pagerLinks,
                    'term' => $term,
                    'message' => false,
                    'title' => 'Results for "' . ucfirst($term) . '"',
                    'facets' => $facets,
                    'url' => $requestUrl,
                    'query' => $request->fullUrl(),
                    'clearedQuery' => $requestUrl . '?' . http_build_query($clearedParams),
                    'show_clear' => true,
                ]);
            }
            return view('result', [
                'videos' => false,
                'pagerLinks' => [],
                'term' => false,
                'message' => 'No results found',
                'title' => '',
                'url' => $requestUrl,
                'query' => $request->fullUrl(),
                'clearedQuery' => $requestUrl,
                'facets' => false,
                'show_clear' => true
            ]);
        }
        return view('result', [
            'videos' => false,
            'pagerLinks' => [],
            'term' => false,
            'message' => 'No search term entered.',
            'title' => '',
            'url' => $requestUrl,
            'query' => $request->fullUrl(),
            'clearedQuery' => $requestUrl,
            'facets' => false,
            'show_clear' => true
        ]);
    }

    /**
     * Clear unwanted sorting parameters
     *
     * @param $params
     * @return mixed
     */
    public function clearParams($params)
    {
        if (isset($params['sort']) && isset($params['order'])) {
            unset($params['sort']);
            unset($params['order']);
        }
        if (isset($params['start'])) {
            unset($params['start']);
        }
        return $params;
    }
}
