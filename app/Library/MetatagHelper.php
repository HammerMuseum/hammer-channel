<?php

namespace App\Library;

use Illuminate\Http\Request;

/**
 * Class MetatagHelper
 * @package App\Library
 */
class MetatagHelper
{
    /** @var Request */
    protected $request;

    /**
     * MetatagHelper constructor.
     * @param Request $request
     */
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getCanonicalUrl()
    {
        return url()->current();
    }

    /**
     * @return string
     */
    public function getCurrentUrl()
    {
        return url()->full();
    }

    /**
     * @return string
     */
    public function getPlayerUrl($id)
    {
        $params = [$id];

        if ($this->request->query->has('start')) {
            $params['start'] = $this->request->get('start');
        }

        if ($this->request->query->has('end')) {
            $params['end'] = $this->request->get('end');
        }

        return route('video.container', $params);
    }

    /**
     * @param $data
     * @return string
     */
    public function getImageUrl($id = null)
    {
        if (!is_null($id)) {
            return route('images', [$id, 'medium']);
        }
        return config('app.url') . '/images/logo-hammer-video.png';
    }
}
