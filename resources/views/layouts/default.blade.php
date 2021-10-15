<!DOCTYPE html>
<html lang="{{config('app.locale')}}">
<head>
    <title>@yield('title', '首页') - Micro-Blog</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>
<body>

{{-- 引入局部视图-头部 --}}
@include('layouts._header')

<div class="container">
    <div class="offset-md-1 col-md-10">
        {{--引入提示信息--}}
        @include('shared._message')
        @yield('content')
        {{-- 引入局部视图 footer --}}
        @include('layouts._footer')
    </div>
</div>
</body>
<script src="{{mix('js/app.js')}}"></script>
</html>