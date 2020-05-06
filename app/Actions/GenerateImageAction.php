<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;
use Image;
use Intervention\Image\Exception\NotReadableException;

class GenerateImageAction
{
    /**
     * Generates a thumbnail image.
     *
     * @param string $id
     *  The requested image identifier.
     *
     * @param string $size
     *  The requested image size.
     */
    public function execute($id, $size)
    {
        $baseUrl = config('constants.imagesPath');
        if (empty($baseUrl)) {
            Log::error('Images base URL not set.');
            abort(503);
        }

        $url = "{$baseUrl}{$id}";
        $className = "App\\Filters\\" . ucfirst($size);
        if (class_exists($className)) {
            $filter = new $className();
        } else {
            abort(404);
        }

        try {
            $img = Image::cache(function ($image) use ($url, $filter) {
                $image->make($url)->filter($filter);
            }, 10080, true);
            return $img;
        } catch (NotReadableException $e) {
            Log::error('failed to read source image.', ['context' => $url]);
            abort(404);
        }
    }
}
