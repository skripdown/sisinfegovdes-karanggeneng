@php
    $admin_class = ' admin';
    $developer_class = ' developer';
    if (!\App\Http\back\_App::admin())
        $admin_class .= ' admin-off';
    if (!\App\Http\back\_App::developer())
        $developer_class .= ' developer-off';
@endphp

<!DOCTYPE html>
<html lang="{{env('APP_LANG')}}" dir="{{env('APP_DIR')}}">
<head>
    <title>@yield('title')</title>
    <meta charset="{{env('APP_CHARSET')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="{{env('APP_VIEWPORT')}}">
    <meta name="description" content="{{env('APP_DESCRIPTION')}}">
    <meta name="author" content="{{env('APP_AUTHOR')}}">
    <link rel="icon" type="image/png" href="{{asset(env('ICON_PATH'))}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/c3/c3.min.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/jvector/jquery-jvectormap-2.0.2.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'extra/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset(env('LIB_PATH').'core/skripdown/directory.css')}}">
    <link rel="stylesheet" href="{{asset(env('CSS_PATH').'style.min.css')}}">
    <link rel="stylesheet" href="{{asset(env('CSS_PATH').'preloader.css')}}">
    <link rel="stylesheet" href="{{asset(env('CSS_PATH').'added.css')}}">
    <link rel="stylesheet" href="{{asset(env('CSS_PATH').'admin-app.css')}}">
    <!--suppress CssUnusedSymbol -->
    <style>#info-user-bg{background-image:url("{{asset('asset/bg-blank.png')}}");position:relative;height:8rem;margin-bottom:-7rem;}</style>
    @include('root.preloader_style')
    @yield('style-head')
    <script src="{{asset(env('LIB_PATH').'core/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'extra/html5shiv/html5shiv.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'extra/respond/respond.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_response.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_ui_factory.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_enc.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_data.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_formdata.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_properties.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_dir.js')}}"></script>
    <script src="{{asset(env('JS_SUB').'form-control.js')}}"></script>
    @yield('script-head')
</head>
<body>
@include('root.preloader')
<div id="main-wrapper"  data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    <header class="topbar" data-navbarbg="skin6">
        <nav class="navbar top-navbar navbar-expand-md">
            <div class="navbar-header" data-logobg="skin6">
                <a href="javascript:void(0)" class="nav-toggler waves-effect waves-light d-block d-md-none">
                    <i class="ti-menu ti-close"></i>
                </a>
                <div class="navbar-brand">
                    <a href="@yield('page_focus')">
                        <b class="logo-icon">
                            <img src="{{asset(env('ICON_PATH'))}}" alt="homepage" class="dark-logo" style="width: 4em;margin-left: 2em;margin-top: 2em;">
                            <img src="{{asset(env('ICON_PATH'))}}" alt="homepage" class="light-logo" style="width: 4em;">
                        </b>
                        <span class="logo-text" >
                        </span>
                    </a>
                </div>
                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ti-more"></i>
                </a>
            </div>
            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                </ul>
                <ul class="navbar-nav float-right">
                    <li class="nav-item dropdown">
                        <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="ml-2 d-none d-lg-inline-block">
                                    <span class="text-dark">
                                        Profil
                                        <i data-feather="chevron-down" class="svg-icon"></i>
                                    </span>
                                </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <form class="dropdown-item">
                                <i data-feather="settings" class="svg-icon mr-2 ml-1"></i>
                                <a href="{{route('settings')}}" class="btn">Pengaturan</a>
                            </form>
                            <form class="dropdown-item">
                                <i data-feather="activity" class="svg-icon mr-2 ml-1"></i>
                                <a href="{{route('my_activities')}}" class="btn">Aktivitas Saya</a>
                            </form>
                            <form action="{{route('logout')}}" method="post" class="dropdown-item">
                                @csrf
                                <i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                <input type="submit" value="Keluar" class="btn">
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar" data-sidebarbg="skin6">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="sidebar-item">
                        <a class="sidebar-link sidebar-link" href="{{url('/dashboard')}}" aria-expanded="false">
                            <i data-feather="home" class="feather-icon"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    @if (\App\Http\back\_Authorize::chief())
                        <li class="sidebar-item{{$admin_class}}">
                            <a class="sidebar-link" href="{{url('/approvals'.\App\Http\back\_UI::$FLAG_UI.\App\Http\back\_UI::$FLAG_RELATION.'(modifies,officer,officer.user)')}}" aria-expanded="false">
                                <i data-feather="check-circle" class="feather-icon"></i>
                                <span class="hide-menu">Persetujuan</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Publication::class))
                        <li class="sidebar-item{{$admin_class}}">
                            <a class="sidebar-link" href="{{url('/albums'.\App\Http\back\_UI::$FLAG_UI)}}" aria-expanded="false">
                                <i data-feather="image" class="feather-icon"></i>
                                <span class="hide-menu">Album Galeri</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link{{$admin_class}}" href="{{url('/blogs'.\App\Http\back\_UI::$FLAG_UI)}}" aria-expanded="false">
                                <i data-feather="file-text" class="feather-icon"></i>
                                <span class="hide-menu">Agenda & Berita</span>
                            </a>
                        </li>
                    @endif
                    @if(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Account::class) || \App\Http\back\_Authorize::manage(\App\Http\back\authorize\Developer::class) || \App\Http\back\_Authorize::chief())
                        <li class="sidebar-item{{$admin_class}}">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="eye" class="feather-icon"></i>
                                <span class="hide-menu">Pusat Kontrol</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level base-level-line">
                                @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Account::class))
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{url('/roles'.\App\Http\back\_UI::$FLAG_UI)}}" aria-expanded="false">
                                            <span class="hide-menu">Hak Akses</span>
                                        </a>
                                    </li>
                                @endif
                                @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Developer::class) || \App\Http\back\_Authorize::chief())
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{route('dev_activities')}}" aria-expanded="false">
                                            <span class="hide-menu">Aktivitas Pengguna</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{url('/logs'.\App\Http\back\_UI::$FLAG_UI.\App\Http\back\_UI::$FLAG_RELATION.'(client,logip,logip.ip,logdevice,logdevice.device,logbrowser,logbrowser.browser,logoperatingsystem,logoperatingsystem.operatingsystem)')}}" aria-expanded="false">
                                            <span class="hide-menu">Log Info Sistem</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item{{$developer_class}}">
                                        <a class="sidebar-link" href="{{url('/')}}" aria-expanded="false">
                                            <span class="hide-menu">Developer</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Civil::class))
                        <li class="sidebar-item{{$admin_class}}">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="users" class="feather-icon"></i>
                                <span class="hide-menu">Kependudukan</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level base-level-line">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{url('/registrations'.\App\Http\back\_UI::$FLAG_UI)}}" aria-expanded="false">
                                        <span class="hide-menu">Registrasi</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/districts'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Dusun</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/hamlets'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">RT</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/neighboors'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">RW</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/families'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Keluarga</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/citizens'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Penduduk</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Employee::class))
                        <li class="sidebar-item{{$admin_class}}">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="trello" class="feather-icon"></i>
                                <span class="hide-menu">Kepegawaian</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level base-level-line">
                                <li class="sidebar-item">
                                    <a href="{{url('/officers'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Data Pegawai</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/mutins'.\App\Http\back\_UI::$FLAG_UI.\App\Http\back\_UI::$FLAG_RELATION.'(modifies)')}}" class="sidebar-link">
                                        <span class="hide-menu">Mutasi Masuk</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/mutouts'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Mutasi Keluar</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/expires'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Pensiun</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Archive::class))
                        <li class="sidebar-item{{$admin_class}}">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="mail" class="feather-icon"></i>
                                <span class="hide-menu">Surat Daring</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level base-level-line">
                                <li class="sidebar-item">
                                    <a href="{{url('/requestarchives'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Permohonan Surat</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{url('/archives'.\App\Http\back\_UI::$FLAG_UI)}}" class="sidebar-link">
                                        <span class="hide-menu">Arsip Surat</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @yield('sidebar-extension')
                </ul>
            </nav>
        </div>
    </aside>
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">@yield('page-breadcrumb')</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item">
                                    @yield('sub-breadcrumb')
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            @yield('content')
            @yield('popup')
        </div>
        <footer class="footer text-center text-muted">
            <p class="text-center text-muted pt-1">Copyright © {{env('APP_YEAR')}}. From <a href="{{env('APP_AUTHOR_INFO')}}" target="_blank" class="px-1 font-weight-bold text-dark">{{env('APP_NAME')}}</a> All rights reserved.</p>
        </footer>
    </div>
</div>
<script src="{{asset(env('LIB_PATH').'core/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'core/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset(env('JS_PATH').'app-style-switcher.js')}}"></script>
<script src="{{asset(env('JS_PATH').'feather.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'core/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{asset(env('JS_PATH').'sidebarmenu.js')}}"></script>
<script src="{{asset(env('JS_PATH').'custom.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/c3/d3.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/c3/c3.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/jvector/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/jvector/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{asset(env('JS_PATH').'pages/dashboards/dashboard1.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'extra/prism/prism.js')}}"></script>
<script src="{{asset(env('LIB_PATH').'core/chart.js/dist/Chart.min.js')}}"></script>
@yield('script-body')
</body>
</html>
