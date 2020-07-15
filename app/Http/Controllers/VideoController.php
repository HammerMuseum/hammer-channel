<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;
use App\Library\MetatagHelper;

/**
 * Class VideoController
 * @package App\Http\Controllers
 */
class VideoController extends Controller
{
    /** @var Api */
    protected $api;

    /** @var MetatagHelper */
    protected $metatagHelper;

    /**
     * VideoController constructor.
     * @param Api $api
     * @param MetatagHelper $metatagHelper
     */
    public function __construct(
        Api $api,
        MetatagHelper $metatagHelper
    ) {
        $this->api = $api;
        $this->metatagHelper = $metatagHelper;
    }

    /**
     * Returns an iframe response for video embed.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function container(Request $request, $id)
    {
        $data = $this->api->request('videos/' . $id);

        if (empty($data['data'])) {
            abort(404);
        }

        return view('embed', [
            'video' => $data['data'][0],
        ]);
    }

    /**
     * View for a single video.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request, $id, $slug)
    {
        $path = '/video/' . $id . '/' . $slug;
        $data = $this->api->request('videos/' . $id);

        if (empty($data['data'])) {
            abort(404);
        }

        $this->setMeta($data['data'][0]);

        return view('main', [
            'state' => $this->getAppState($path, $data),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewJson(Request $request, $id, $slug)
    {
        $path = '/video/' . $id . '/' . $slug;
        $data = $this->api->request('videos/' . $id);
        $state = $this->getAppState($path, $data);
        return response()->json($state);
    }

    /**
     * @param $data
     * @return array
     */
    public function getAppState($path, $data)
    {
        $state = [];
        $state['path'] = $path;
        if (!empty($data['data'])) {
            $state['video'] = $data['data'][0];
        }
        return $state;
    }

    /**
     * @param $data
     * @return array
     */
    public function setMeta($data)
    {
        $pageUrl = $this->metatagHelper->getCurrentUrl();
        meta()
            ->set('title', $data['title'])
            ->set('canonical', $pageUrl)
            ->set('description', $data['description'])
            ->set('og:description', $data['description'])
            ->set('twitter:url', $pageUrl)
            ->set('twitter:title', $data['title'])
            ->set('twitter:description', $data['description'])
            ->set('og:url', $pageUrl)
            ->set('og:title', $data['title'])
            ->set('og:description', $data['description']);

        if (isset($data['thumbnailId'])) {
            $imageUrl = $this->metatagHelper->getImageUrl($data['thumbnailId']);
            meta()
                ->set('twitter:image', $imageUrl)
                ->set('og:image', $imageUrl)
                ->set('og:image:type', 'image/jpg')
                ->set('og:image:width', 320)
                ->set('og:image:height', 180);
        }

        if (isset($data['asset_id'])) {
            $playerUrl = $this->metatagHelper->getPlayerUrl($data['asset_id']);
            meta()
                ->set('twitter:player', $playerUrl)
                ->set('twitter:card', 'player')
                ->set('twitter:player:width', 480)
                ->set('twitter:player:height', 480)
                ->set('og:video', $playerUrl)
                ->set('og:video:secure_url', $playerUrl)
                ->set('og:video:type', 'video/mp4')
                ->set('og:video:width', 480)
                ->set('og:video:height', 480);
        }
    }
}
