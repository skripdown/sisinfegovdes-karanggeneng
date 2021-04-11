<!DOCTYPE html>
<html lang="{{env('APP_LANG')}}">
<head>
    <title>Album</title>
    <meta name="viewport" content="{{env('APP_VIEWPORT')}}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="{{env('ICON_SIZE')}}" href="{{asset(env('ICON_PATH'))}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/owl-carousel/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/owl-carousel/css/owl.theme.default.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/vivify/vivify.min.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/aos/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset(env('CSS_PATH').'landing/style.min.css')}}">
    <link rel="stylesheet" href="{{asset(env('CSS_PATH').'added.css')}}">
</head>
<body id="body" data-spy="scroll" data-target=".navbar" data-offset="100">
<header id="header-section">
    <nav class="navbar navbar-expand-lg pl-3 pl-sm-0" id="navbar">
        <div class="container">
            <div class="navbar-brand-wrapper d-flex w-100">
                <img src="{{asset(env('ICON_PATH'))}}" alt="" width="80">
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="mdi mdi-menu navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse navbar-menu-wrapper" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-lg-center align-items-start ml-auto">
                    <li class="d-flex align-items-center justify-content-between pl-4 pl-lg-0">
                        <div class="navbar-collapse-logo">
                            <img src="{{asset(env('ICON_PATH'))}}" alt="" width="80">
                        </div>
                        <button class="navbar-toggler close-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="mdi mdi-close navbar-toggler-icon pl-5"></span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/')}}">Beranda</a>
                    </li>
                    @if (\App\Http\back\_Authorize::login())
                        <li class="nav-item">
                            <a class="btn btn-success">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{url('/login')}}" class="btn btn-success" type="button">Masuk</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
<div class="banner d-none">
    <div class="container">
        <h1 class="font-weight-semibold">{{env('APP_NAME')}}: {{env('APP_DESCRIPTION')}}</h1>
        <h6 class="font-weight-normal text-muted pb-3">{{env('APP_SUBTITLE')}}</h6>
        <img src="{{asset('asset/app_logo.png')}}" alt="" class="img-fluid mt-5 mb-5" style="height: 40vh">
    </div>
</div>
<div class="content-wrapper">
    <div class="container">
        <section class="features-overview" id="features-section" >
            <div class="content-header">
                <h2>Album Foto</h2>
                <h6 class="section-subtitle text-muted">Publikasi Album Kegiatan Desa Karanggeneng.</h6>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="{{url('/album')}}">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 1</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 2</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 3</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Album 4</h5>
                        <a href="">
                            <img src="{{asset('asset/pdip.png')}}" alt="" width="200" height="200">
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <footer class="border-top">
            <p class="text-center text-muted pt-4">Copyright © {{env('APP_YEAR')}}. From <a href="{{env('APP_AUTHOR_INFO')}}" target="_blank" class="px-1 font-weight-bold text-dark">{{env('APP_NAME')}}</a>All rights reserved.</p>
        </footer>
    </div>
</div>
<script src="{{asset(env('LIB_PATH').'core/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'core/bootstrap/dist/js/bootstrap.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/owl-carousel/js/owl.carousel.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/aos/js/aos.js')}}"></script>
<script src="{{asset(env('JS_PATH').'feather.min.js')}}"></script>
<script src="{{asset(env('JS_PATH').'landingpage.js')}}"></script>
</body>
</html>

