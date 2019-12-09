<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;

class VideoController extends Controller
{
    public function view(Request $request, $id)
    {
        $api = new Api();

        $data = $api->request('video', $id);

        if (isset($data['success']) && $data['success']) {
            return view('video', [
                'data' => $data['data'],
                'message' => false
            ]);
        }
        return view('video', [
           'data' => false,
           'message' => $data['message']
        ]);
    }
}
