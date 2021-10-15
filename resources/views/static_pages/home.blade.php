@extends('layouts.default')

@section('content')
    @if(Auth::check())
        {{--登陆用户展示--}}
        <div class="row">
            <div class="col-md-8">
                <section class="status_form">
                    @include('shared._status_form')
                </section>
                <h4>微博列表</h4>
                <hr>
                @include('shared._feed')
            </div>
            <aside class="col-md-4">
                <section class="user_info">
                    @include('shared._user_info', ['user' => Auth::user()])
                </section>
                <section class="stats mt-2">
                    @include('shared._stats', ['user' => Auth::user()])
                </section>
            </aside>
        </div>
    @else
        {{--未登录用户展示--}}
        <div class="jumbotron">
            <h1>Micro-Blog</h1>
            <p class="lead">
                基于
                <a href="https://laravel.com/">Laravel 6.2</a>
                的仿微博 WEB 应用
            </p>
            <p> The PHP Framework for Web Artisans </p>
            <p>
                <a class="btn btn-lg btn-success" href="{{route('signup')}}" role="button">现在注册</a>
            </p>
        </div>
    @endif
@stop