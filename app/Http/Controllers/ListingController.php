<?php

namespace App\Http\Controllers;

use App\Api;
use App\Library\Pagination;
use App\Library\Metadata;
use Illuminate\Http\Request;

/**
 * Class ListingController
 * @package App\Http\Controllers
 */
class ListingController extends Controller
{
    /** @var Api */
    protected $api;

    /** @var Pagination */
    protected $pagination;

    /** @var Metadata */
    protected $metadata;

    /**
     * ListingController constructor.
     * @param Api $api
     * @param Pagination $pagination
     * @param Metadata $metadata
     */
    public function __construct(
        Api $api,
        Pagination $pagination,
        Metadata $metadata
    ) {
        $this->api = $api;
        $this->pagination = $pagination;
        $this->metadata = $metadata;
    }

    /**
     * Fetches all videos from API and returns the template listing.blade.php
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $videos = $this->api->request('term', 'term=all');
        return view('app', [
            'state' => $this->getAppState($videos, $params),
            'metadata' => $this->getMetadata($videos)
        ]);
    }

    /**
     * Return a JSON version of the index action
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexJson(Request $request)
    {
        $params = $request->all();
        $videos = $this->api->request('term', 'term=all');
        $state = $this->getAppState($videos, $params);
        return response()->json($state);
    }

    /**
     * Get JSON of the app state to inject into the head and populate the frontend
     *
     * @param $data
     * @param $params
     * @return array
     */
    public function getAppState($data, $params)
    {
        $pagerLinks = [];
        if (!empty($data['pages'])) {
            $pagerLinks = $this->pagination->pagerLinks($data['pages']['pager']);
        }

        $topics = [];
        if (isset($data['aggregations']) && isset($data['aggregations']['topics'])) {
            foreach ($data['aggregations']['topics']['buckets'] as $topic) {
                $topics[$topic['key']]['id'] =  strtolower(str_replace([' ', '&'], '', $topic['key']));
                $topics[$topic['key']]['videos'] = $topic['by_topic']['hits']['hits'];
                $topics[$topic['key']]['count'] = $topic['doc_count'];
            }
        }
        return [
            'path' => '/',
            'videos' => isset($data['data']) ? $data['data'] : [],
            'pager' => $pagerLinks,
            'message' => isset($data['message']) ? $data['message'] : false,
            'title' => '',
            'show_clear' => false,
            'topics' => $topics,
            'clearedPageQuery' => $this->pagination->clearParams($params, ['start']),
            'clearedSortQuery' => $this->pagination->clearParams($params, ['sort', 'order']),
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function getMetadata($data)
    {
        return $this->metadata->getMetadata($data);
    }

    /**
     * List videos by topic
     *
     * @param Request $request
     * @param $keyword
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topic(Request $request, $keyword)
    {
        $queryString = http_build_query(['tags' => $keyword]);
        $result = $this->api->request('search', $queryString);
        if (isset($result['success']) && $result['success']) {
            $requestUrl = $request->url();
            $pagerLinks = [];
            if (!empty($videos['pages'])) {
                $pagerLinks = $this->pagination->pagerLinks($requestUrl, $videos['pages']['pager']);
            }
            return view('listing', [
                'videos' => $result['data'],
                'message' => false,
                'title' => ucfirst($keyword),
                'pagerLinks' => $pagerLinks,
                'show_clear' => false
            ]);
        }
        return view('listing', [
            'videos' => false,
            'message' => 'No videos available.',
            'title' => '',
            'pagerLinks' => [],
            'show_clear' => false,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions()
    {
        $cannedTerms = [];
        $featuredPlaylist = $this->api->request('playlists/Featured');
        if (isset($featuredPlaylist['success']) && $featuredPlaylist['success']) {
            $playlistData = $featuredPlaylist['data'];
            foreach ($playlistData['videos'] as $video) {
                if (!empty($video['topics'])) {
                    foreach ($video['topics'] as $topic) {
                        $cannedTerms[] = [
                            'term'=> $topic,
                            'query' => [
                                'topics' => $topic
                            ]
                        ];
                    }
                }
                if (!empty($video['tags'])) {
                    foreach ($video['tags'] as $tag) {
                        $cannedTerms[] = [
                            'term' => $tag,
                            'query' => [
                                'tags' => $tag
                            ]
                        ];
                    }
                }
                if (isset($video['people'])) {
                    foreach ($video['people'] as $person) {
                        $cannedTerms[] = [
                            'term' => $person,
                            'query' => [
                                'speakers' => $person
                            ]
                        ];
                    }
                }
            }
        }
        // Randomise and limit to 12
        if (!empty($cannedTerms)) {
            shuffle($cannedTerms);
            $cannedTerms = array_slice($cannedTerms, 0, 12);
        }
        return response()->json($cannedTerms);
    }
}
