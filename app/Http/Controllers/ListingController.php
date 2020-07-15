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
     * Fetches our custom front page listing.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $videos = $this->api->request('search/aggregate/topics');
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
        $videos = $this->api->request('search/aggregate/topics');
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

        return [
            'path' => '/',
            'videos' => isset($data['data']) ? $data['data'] : [],
            'pager' => $pagerLinks,
            'message' => isset($data['message']) ? $data['message'] : false,
            'title' => '',
            'clearedPageQuery' => $this->pagination->clearParams($params, ['page']),
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestionsJson()
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
