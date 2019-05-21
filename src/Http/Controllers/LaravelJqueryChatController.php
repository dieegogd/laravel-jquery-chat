<?php

namespace Dieegogd\LaravelJqueryChat\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaravelJqueryChatController extends Controller
{

    public function index(Request $request)
    {
       return view('laravel-jquery-chat::list');
    }
}
