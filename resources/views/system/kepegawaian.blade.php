@extends('system.template')


@section('title')
    kepegawaian
@endsection

@section('page-description')
    sistem informasi administrasi kepegawaian desa Karanggeneng kabupaten Lamongan
@endsection

@section('nav-title')
    SIMPEG
@endsection

@section('app-nav')
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#officer-container">PEGAWAI</a>
    </li>
@endsection

@section('page-title')
    Kepegawaian
@endsection

@section('page-subtitle')
    Sistem Informasi Administrasi Kepegawaian
@endsection

@section('content-body')
    <div class="container pt-4 pb-4 mt-4 mb-4"></div>
    <div class="container-fluid pt-4 pb-4 mt-4 mb-4">
        <section class="pt-5 pb-5">
            <div class="container">
                <div class="row d-flex">
                    <div class="col-12">
                        <h2 class="mb-2 text-center">Data Pegawai {{env('APP_NAME')}}</h2>
                        <p class="mb-5 text-center">Profil Data Kepegawaian {{env('APP_NAME').' '.env('APP_DESCRIPTION')}}</p>
                    </div>
                    <div class="text-center w-100 d-flex justify-content-center" id="officer-container">
                        <div class="col-sm-6 col-md-4 d-flex">
                            <div class="card card-body border-light bg-banner text-white shadow">
                                <div class="text-dark mb-2">
                                </div>
                                <div class="text-center mb-3" style="">
                                    <img class="img-fluid rounded-circle shadow w-75" src="https://via.placeholder.com/40x40/ccc/ffffff " alt="author avatar">
                                </div>
                                <hr>
                                <div class="d-flex align-items-center">
                                    <div class="m-2">
                                        <a class="text-dark font-weight-bold h4 text-decoration-none" href="#">Nama Pegawai Pertama</a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="ml-2">
                                        <a class="text-muted font-weight-bold h6 text-decoration-none" href="#">Nama Pegawai Pertama</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _officers = _data.officer;
        _officers.refresh(function () {
            _response.get('{{url('/officers'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(user,user.role)')}}', false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function makeOfficer(officer) {
            const pic_admin = '{{asset(env('PATH_ADMIN_PROFILE'))}}/';
            return '<div class="col-sm-6 col-md-4 d-flex"><div class="card card-body border-light bg-banner text-white shadow"><div class="text-dark mb-2"></div><div class="text-center mb-3" style=""><img class="img-fluid rounded-circle shadow w-75" src="' + pic_admin + officer.user.pic + '" alt="author avatar"></div><hr><div class="d-flex align-items-center"><div class="m-2"><a class="text-dark font-weight-bold h4 text-decoration-none" href="#">' + officer.user.name + '</a></div></div><div class="d-flex align-items-center"><div class="ml-2"><a class="text-muted font-weight-bold h6 text-decoration-none" href="#">' + officer.identity + '</a></div></div></div></div>';
        }
        const panel   = document.getElementById('officer-container');
        let node_iter = _officers._first;
        let res       = '';
        while (node_iter !== undefined) {
            res += makeOfficer(node_iter);
            node_iter = node_iter._next;
        }
        panel.innerHTML = res;
    </script>
@endsection
