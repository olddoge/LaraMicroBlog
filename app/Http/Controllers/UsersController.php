<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    /**
     * @param User $user 用户模型
     * @return Application|Factory|View
     */
    public function show(User $user)
    {
        // compact('user') 等价于 ['user' => $user]
        return view('users.show', compact('user'));
    }

    /**
     * 用户注册相关
     * @param Request $request
     */
    public function store(Request $request)
    {
        // 验证提交的请求数据
        // 第二个参数为验证规则
        $this->validate($request, [
            'name'     => 'required|unique:users|max:50',
            'email'    => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        // 通过验证后写入数据库
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // 增加一个成功信息的提示
        $success_tips = '欢迎，您将在这里开启一段新的旅程~';
        session()->flash('success', $success_tips);
        // 最后重定向回 show 视图
        return redirect()->route('users.show', [$user]);
    }
}
