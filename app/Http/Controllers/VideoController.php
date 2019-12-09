<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function view(Request $request, $id)
    {
        $testData = [
            'title' => 'Naomi Klein: The Case for a Green New Deal',
            'description' => 'One of the foremost chroniclers of the economic war waged on both people and planet, Naomi Klein champions a sweeping environmental agenda with justice at its center. Her new collection, On Fire: The (Burning) Case for a Green New Deal, pairs over a decade of Klein’s impassioned writing with new material on our immediate political and economic choices. Klein argues that we will rise to the existential challenge of climate change only if we are willing to transform the systems that produced the crisis. She is joined in conversation by Aquilina Soriano Versoza, executive director of the Pilipino Worker’s Center of Southern California.',
            'date_recorded' => 'Oct 28, 2019',
            'duration' => '1:37:10'
        ];
        return view('video', [
            'data' => $testData
        ]);
    }
}
