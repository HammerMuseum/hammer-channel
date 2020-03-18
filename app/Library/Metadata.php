<?php

namespace App\Library;

use Illuminate\Http\Request;

/**
 * Class Metadata
 * @package App\Library
 */
class Metadata
{
    /** @var \Illuminate\Config\Repository|mixed */
    protected $siteName;

    /** @var Request */
    protected $request;

    /**
     * Metadata constructor.
     * @param Request $request
     */
    public function __construct(
        Request $request
    ) {
        $this->siteName = config('app.name');
        $this->request = $request;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getMetadata($data = [])
    {
        $metadata = [];
        $metadata['site_name'] = $this->siteName;
        $metadata['url'] = $this->getMetadataUrl();
        $metadata['title'] = $this->getMetadataTitle($data);
        $metadata['description'] = $this->getMetadataDescription($data);
        $metadata['image'] = $this->getMetadataImage($data);
        return $metadata;
    }

    /**
     * @param $data
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getMetadataTitle($data)
    {
        if (isset($data['title'])) {
            return $data['title'];
        }
        return config('app.name');
    }

    /**
     * @param $data
     * @return string
     */
    public function getMetadataDescription($data)
    {
        if (isset($data['description'])) {
            return $data['description'];
        }
        return 'In the Hammer\'s video archive, people will discover 
        ideas that will illuminate their lives in new ways.';
    }

    /**
     * @return string
     */
    public function getMetadataUrl()
    {
        return $this->request->url();
    }

    /**
     * @param $data
     * @return string
     */
    public function getMetadataImage($data)
    {
        if (isset($data['thumbnail_url'])) {
            return $data['thumbnail_url'];
        }
        return config('app.url') . '/assets/images/logo-hammer-video.png';
    }
}
