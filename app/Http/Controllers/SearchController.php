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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        $params = $request->all();
        if (!is_null($term)) {
            $results = $this->api->request('search/' . $term, http_build_query($params));
            if ($results && !isset($results['error']) && isset($results['data'])) {
                $pagerLinks = [];
                $requestUrl = $request->url();
                if (!empty($videos['pages'])) {
                    $pagerLinks = $this->pagination->pagerLinks($requestUrl, $videos['pages']['pager']);
                }
                $facets = $this->facetHandler->getFacetOptions($results['aggregations']);
                return view('result', [
                    'videos' => $results['data'],
                    'pagerLinks' => $pagerLinks,
                    'term' => $term,
                    'message' => false,
                    'title' => 'Results for "' . ucfirst($term) . '"',
                    'facets' => $facets,
                    'show_clear' => true,
                ]);
            }
            return view('result', [
                'videos' => false,
                'term' => false,
                'pagerLinks' => [],
                'message' => 'No results found',
                'title' => '',
                'facets' => false
            ]);
        }
        return view('result', [
            'videos' => false,
            'term' => false,
            'pagerLinks' => [],
            'message' => 'No search term entered.',
            'title' => '',
            'facets' => false
        ]);
        // api/search/hammer?facets[0]date=2014:facets[1]speaker=lad&sort=date&order=desc
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
            $results = $this->api->request('search/filter/' . $term, http_build_query($queryParams));
            $pagerLinks = [];
            $requestUrl = $request->url();
            if (!empty($videos['pages'])) {
                $pagerLinks = $this->pagination->pagerLinks($requestUrl, $videos['pages']['pager']);
            }
            if ($results['success'] && !isset($results['error'])) {
                $facets = $this->facetHandler->getFacetOptions($results['aggregations']);
                return view('result', [
                    'videos' => $results['data'],
                    'pagerLinks' => $pagerLinks,
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
            'pagerLinks' => [],
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
                    'pagerLinks' => [],
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
