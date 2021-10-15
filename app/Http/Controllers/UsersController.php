<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mail;
use Auth;

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
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
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
        $statuses = $user->statuses()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        // compact('user') 等价于 ['user' => $user]
        return view('users.show', compact('user', 'statuses'));
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
        // 发送确认邮件
        $this->sendEmailConfirmationTo($user);
        $tips = '验证邮件已发送到你的注册邮箱上，请注意查收。';
        session()->flash('success', $tips);
        return redirect('/');
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

    /**
     * 发送确认邮件
     * @param $user
     */
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册！请确认你的邮箱。";
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    /**
     * 激活确认
     * @param $token
     * @return RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
