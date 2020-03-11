<?php

namespace App\Library;

use Illuminate\Http\Request;

class Metadata
{
    protected $siteName;
    protected $request;

    public function __construct(
        Request $request
    ) {
        $this->siteName = config('app.name');
        $this->request = $request;
    }


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

    public function getMetadataTitle($data)
    {
        if (isset($data['title'])) {
            return $data['title'];
        }
        return config('app.name');
    }

    public function getMetadataDescription($data)
    {
        if (isset($data['description'])) {
            return $data['description'];
        }
        return 'Some app description value';
    }

    public function getMetadataUrl()
    {
        return $this->request->url();
    }

    public function getMetadataImage($data)
    {
        if (isset($data['thumbnail_url'])) {
            return $data['thumbnail_url'];
        }
        return 'some_default_image.jpg';
    }
}
