<?php

namespace App\Http\Controllers;

use App\Actions\GenerateImageAction;

class GenerateImageController extends Controller
{
    /**
     * Handle the incoming request.

     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, $size, GenerateImageAction $generateImageAction)
    {
        $img = $generateImageAction->execute($id, $size);
        return $img->response();
    }
}
