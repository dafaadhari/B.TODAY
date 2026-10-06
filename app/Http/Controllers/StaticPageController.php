<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;

class StaticPageController extends Controller
{
    public function pasangIklan()
    {
        $ads = Advertisement::all();
        return view('pasang-iklan', compact('ads'));
    }

    public function redaksi()
    {
        return view('redaksi');
    }
}
