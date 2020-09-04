<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Image;
use Intervention\Image\Exception\NotReadableException;

class GenerateImageAction
{
    /**
     * The base URL for remote images.
     * @var string $basUrl
     */
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('constants.imagesPath');
    }
    /**
     * Generates a thumbnail image.
     *
     * @param string $template
     *  The requested image template.
     *
     * @param string $id
     *  The requested image identifier.
     */
    public function execute($template, $id)
    {
        $pathinfo = pathinfo($id);
        $filename = $pathinfo['filename'];
        $extension = $pathinfo['extension'];
        $directory = "$template/";
        $cachePath = "$template/$filename/$extension";

        if (empty($this->baseUrl)) {
            Log::error('Images base URL has not been defined.');
            abort(503);
        }

        $filter = $this->getFilter($template);
        if (!$filter) {
            abort(404);
        }

        $image = $this->getImage($id, $filter);
        if (!$image) {
            abort(404);
        }

        if (!Storage::disk('dynamic_images')->exists($directory)) {
            Storage::disk('dynamic_images')->makeDirectory($directory);
        }

        if ($extension === 'webp') {
            Storage::disk('dynamic_images')->put($cachePath, $image->encode('webp', 60)->stream());
        } else {
            Storage::disk('dynamic_images')->put($cachePath, $image->stream('jpg', 60));
        }

        return $image->response();
    }

    /**
     * Get a template object from given template name
     *
     * @param  string $name
     * @return mixed
     */
    private function getFilter($name)
    {
        $className = "App\\Filters\\" . ucfirst($name);
        if (class_exists($className)) {
            return new $className();
        } else {
            return false;
        }
    }

    /**
     * Returns the full image path for a given filename.
     *
     * @param  string $filename
     * @return string
     */
    private function getImage($id, $filter)
    {
        if (!$filter) {
            return false;
        }

        $url = "{$this->baseUrl}{$id}";
        try {
            $img = Image::make($url)->filter($filter);
            return $img;
        } catch (NotReadableException $e) {
            Log::error('failed to read source image.', ['context' => $url]);
            return false;
        }
    }
}
