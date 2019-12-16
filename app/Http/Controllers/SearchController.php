<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Search;

/**
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /** @var Search */
    protected $search;

    /**
     * SearchController constructor.
     * @param Search $search
     */
    public function __construct(
        Search $search
    ) {
        $this->search = $search;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('term');
        if (!is_null($searchTerm)) {
            $results = $this->search->search($searchTerm);

            if ($results) {
                return view('listing', [
                    'videos' => $results,
                    'message' => false,
                    'title' => ucfirst($searchTerm)
                ]);
            }
        }
        return view('listing', [
            'videos' => false,
            'message' => 'No results found',
            'title' => ''
        ]);
    }
}
