<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Auth;

class StaticPagesController extends Controller
{

    /**
     * 首页
     * @return Application|Factory|View
     */
    public function home()
    {
        $feed_items = [];
        if (Auth::check()) {
            $feed_items = Auth::user()->feed()->paginate(10);
        }
        return view('static_pages/home', compact('feed_items'));
    }

    /**
     * 帮助页
     * @return Application|Factory|View
     */
    public function help()
    {
        return view('static_pages.help');
    }

    /**
     * 关于页
     * @return Application|Factory|View
     */
    public function about()
    {
        return view('static_pages.about');
    }
}
