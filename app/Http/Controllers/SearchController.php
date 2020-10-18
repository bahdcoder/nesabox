<?php

namespace App\Http\Controllers;

use App\Site;

class SearchController extends Controller
{
    /**
     * Search sites and servers
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'servers' => auth()
                ->user()
                ->servers()
                ->select(['id', 'name'])
                ->get(),
            'sites' => Site::select(['id', 'name'])->get()
        ]);
    }
}
