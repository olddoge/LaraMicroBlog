<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

/**
 * 会话管理控制器
 */
class SessionsController extends Controller
{
    public function __construct()
    {
        // create 方法只让未注册的用户访问
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 登陆视图
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('session.create');
    }

    /**
     * 登陆验证
     * @param Request $request
     */
    public function store(Request $request)
    {
        // 验证登陆信息
        $credentials = $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required'
        ]);
        // attempt 方法根据数组中的 email 查找数据，然后用 password 匹配数据库是否一致
        // 第二个参数为是否记住用户
        if (\Auth::attempt($credentials, $request->has('remember'))) {
            // 登陆成功
            $tips = '欢迎回来！';
            session()->flash('success', $tips);
            $fallback = route('users.show', \Auth::user());
            return redirect()->intended($fallback);
        } else {
            // 登陆失败
            $tips = '很抱歉，您的邮箱和密码不匹配';
            session()->flash('danger', $tips);
            return redirect()->back()->withInput(); // withInput 可以吧输入带回给模板
        }
    }

    /**
     * 退出清空 session 操作
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy()
    {
        \Auth::logout();
        $tips = '您已成功退出';
        session()->flash('success', $tips);
        return redirect('login');
    }
}
