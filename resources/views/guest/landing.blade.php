<!DOCTYPE html>
<html lang="{{env('APP_LANG')}}">
<head>
    <title>{{env('APP_NAME')}}</title>
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
                        <a class="nav-link" href="#header-section">Beranda <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features-section">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#digital-marketing-section">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#service-section">Layanan</a>
                    </li>
                    @if (\App\Http\back\_Authorize::login())
                        <li class="nav-item">
                            <a href="{{url('/dashboard')}}" class="btn btn-success">Dashboard</a>
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
<div class="banner">
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
            <div class="d-block mb-4 pb-4" style="margin-top: -4rem">
                <h6 class="text-center pb-4 mb-4 text-info" type="button"><a class="text-info" href="{{url('/albums')}}">lihat album lainnya</a></h6>
            </div>
        </section>
        <section class="digital-marketing-service" id="digital-marketing-section">
            <div class="content-header text-center mb-4 pb-2">
                <h2>Berita dan Agenda</h2>
                <h6 class="section-subtitle text-muted pb-4 mb-2">Artikel Seputar Desa Karanggeneng Kabupaten Lamongan.</h6>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-lg-7 grid-margin grid-margin-lg-0" data-aos="fade-right">
                    <h3 class="m-0" ><a href="{{url('/blog')}}">Judul Blog Berita Pertama</a></h3>
                    <div class="col-lg-7 col-xl-6 p-0">
                        <p class="py-4 m-0 text-muted">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur</p>
                    </div>
                </div>
                <div class="col-12 col-lg-5 p-0 img-digital grid-margin grid-margin-lg-0" data-aos="fade-left">
                    <img src="{{asset('asset/pdip.png')}}" alt="" class="img-fluid">
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-lg-7 text-center flex-item grid-margin" data-aos="fade-right">
                    <img src="{{asset('asset/pdip.png')}}" alt="" class="img-fluid">
                </div>
                <div class="col-12 col-lg-5 flex-item grid-margin" data-aos="fade-left">
                    <h3 class="m-0">Judul Blog Berita Kedua</h3>
                    <div class="col-lg-9 col-xl-8 p-0">
                        <p class="py-4 m-0 text-muted">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur</p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center" style="margin: -10rem 0 5rem">
                <div class="col-12 col-lg-7 grid-margin grid-margin-lg-0" data-aos="fade-right">
                    <h3 class="m-0">Judul Blog Berita Ketiga</h3>
                    <div class="col-lg-7 col-xl-6 p-0">
                        <p class="py-4 m-0 text-muted">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur</p>
                    </div>
                </div>
                <div class="col-12 col-lg-5 p-0 img-digital grid-margin grid-margin-lg-0" data-aos="fade-left">
                    <img src="{{asset('asset/pdip.png')}}" alt="" class="img-fluid">
                </div>
            </div>
            <div class="d-block" style="margin-top: -3rem">
                <h6 class="text-center mb-4" type="button"><a class="text-info" href="{{url('/blogs')}}">lihat artikel lainnya</a></h6>
            </div>
        </section>
        <section class="features-overview" id="service-section">
            <div class="content-header">
                <h2>Pelayanan Kami</h2>
                <h6 class="section-subtitle text-muted">Pelayanan Sistem Informasi Yang Kami Sediakan.</h6>
            </div>
            <div class="d-md-flex justify-content-between">
                <div class="grid-margin d-flex justify-content-start text-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Sistem<br>Informasi<br>Administrasi Kependudukan</h5>
                        <a href="{{url('away/kependudukan')}}" target="_blank">
                            <img src="{{asset('asset/kependudukan.png')}}" alt="" width="100" height="100">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-around text-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Sistem Informasi<br>Administrasi Kepegawaian</h5>
                        <a href="{{url('/away/kepegawaian')}}" target="_blank">
                            <img src="{{asset('asset/kepegawaian.png')}}" alt="" width="100" height="100">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-around text-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Sistem Informasi<br>Administrasi Keuangan</h5>
                        <a href="" target="_blank">
                            <img src="{{asset('asset/keuangan.png')}}" alt="" width="100" height="100">
                        </a>
                    </div>
                </div>
                <div class="grid-margin d-flex justify-content-end text-center">
                    <div class="features-width">
                        <h5 class="py-4 text-center">Sistem Informasi<br>Permohonan Surat Daring</h5>
                        <a href="{{url('/away/surat')}}" target="_blank">
                            <img src="{{asset('asset/surat.png')}}" alt="" width="100" height="100">
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
