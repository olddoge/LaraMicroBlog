<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * 用户相关控制器
 */
class UsersController extends Controller
{
    /**
     * 注册视图
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('users.create');
    }
}