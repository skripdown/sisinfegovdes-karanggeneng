<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="@yield('page-description')">
    <meta name="author" content="{{env('APP_NAME')}}">

    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:500,700&display=swap&subset=latin-ext" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600&display=swap&subset=latin-ext" rel="stylesheet">
    <link href="{{asset(env('CSS_SUB').'bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset(env('CSS_SUB').'fontawesome-all.css')}}" rel="stylesheet">
    <link href="{{asset(env('CSS_SUB').'swiper.css')}}" rel="stylesheet">
    <link href="{{asset(env('CSS_SUB').'magnific-popup.css')}}" rel="stylesheet">
    <link href="{{asset(env('CSS_SUB').'styles.css')}}" rel="stylesheet">
    <link href="{{asset(env('CSS_SUB').'added.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">

    <link rel="icon" href="{{asset(env('ICON_PATH'))}}">
    @yield('style-head')
    <script src="{{asset(env('JS_SUB').'jquery.min.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_response.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_data.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_enc.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_formdata.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_ui_factory.js')}}"></script>
    @yield('script-head')
</head>
<body data-spy="scroll" data-target=".fixed-top">

<div class="spinner-wrapper">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>

<nav class="navbar navbar-expand-md navbar-dark navbar-custom fixed-top">
    <a class="navbar-brand logo-text page-scroll" href="{{url('/away')}}">
        <img src="{{asset(env('ICON_PATH'))}}" alt="" style="width: 60px">
        @yield('nav-title')
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-awesome fas fa-bars"></span>
        <span class="navbar-toggler-awesome fas fa-times"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link page-scroll" href="#header">BERANDA <span class="sr-only">(current)</span></a>
            </li>
            @yield('app-nav')
        </ul>
    </div>
</nav>

<header id="header" class="header">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-container">
                        <h1><span id="js-rotating">{{env('APP_NAME')}} : @yield('page-title')</span></h1>
                        <p class="p-heading p-large">@yield('page-subtitle') Desa Karanggeneng Kabupaten Lamongan</p>
                        <a class="btn-solid-lg page-scroll" href="{{url('/login')}}">Masuk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@yield('content-body')

<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="p-small">Copyright © {{env('APP_YEAR')}} <a href="https://www.instagram.com/malkolp">{{env('APP_NAME')}}</a></p>
            </div>
        </div>
    </div>
</div>

<script src="{{asset(env('JS_SUB').'popper.min.js')}}"></script>
<script src="{{asset(env('JS_SUB').'bootstrap.min.js')}}"></script>
<script src="{{asset(env('JS_SUB').'jquery.easing.min.js')}}"></script>
<script src="{{asset(env('JS_SUB').'swiper.min.js')}}"></script>
<script src="{{asset(env('JS_SUB').'jquery.magnific-popup.js')}}"></script>
<script src="{{asset(env('JS_SUB').'morphext.min.js')}}"></script>
<script src="{{asset(env('JS_SUB').'isotope.pkgd.min.js')}}"></script>
<script src="{{asset(env('JS_SUB').'validator.min.js')}}"></script>
<script src="{{asset(env('JS_SUB').'scripts.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'core/chart.js/dist/Chart.min.js')}}"></script>
@yield('script-body')
</body>
</html>
