<?php

namespace App\Http\Controllers;
use App\Models\Info;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $title = Info::where('attribute_name', '=', 'Site_title')->first()->attribute_value;
        return view('home')->withTitle($title);
    }
}
