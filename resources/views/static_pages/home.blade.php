@extends('layouts.default')

@section('content')
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
@stop