<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * 用户相关控制器
 */
class UsersController extends Controller
{

    public function __construct()
    {
        // 使用中间件验证权限，是否登陆，没登录不能访问某些页面
        // 除了 show，create，store 不需要验证
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index']
        ]);
        // 只让未注册的用户访问 create
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 展示用户列表
     * @return Application|Factory|View
     */
    public function index()
    {
        $users = User::paginate(10);    // 分页
        return view('users.index', compact('users'));
    }

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

    /**
     * 编辑视图
     * @param User $user
     * @return Application|Factory|View
     */
    public function edit(User $user)
    {
        // 指定策略
        // update - 指策略中的方法名
        // $user 对应传参策略方法中的第二个参数
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * 更新用户信息
     * @param User    $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        // 对请求进行验证
        $rules = [
            'name'     => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ];
        $this->validate($request, $rules);
        // 更新数据库
        $data = ['name' => $request->name];
        if ($request->password) {
            // 有更新密码才加入
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        $tips = '个人资料更新成功！';
        session()->flash('success', $tips);
        return redirect()->route('users.show', $user->id);
    }

    /**
     * 删除用户
     * @param User $user
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
