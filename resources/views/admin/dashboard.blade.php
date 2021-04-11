@extends('admin.template')

@section('title')
    Dashboard
@endsection

@section('page-breadcrumb')
    Dashboard
@endsection

@section('sub-breadcrumb')
    Dashboard Admin {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="nav-info"></div>
    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Civil::class))<div id="table-civil"></div>@endif
    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Employee::class))<div id="table-employee"></div>@endif
    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Account::class))<div id="table-account"></div>@endif
    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Archive::class))<div id="table-archive"></div>@endif
    @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Publication::class))<div id="table-publication"></div>@endif
@endsection

@section('popup')
@endsection

@section('script-head')
    <script>@include('root.token')</script>
    @include('admin.script.civil-data')
    @include('admin.script.account-data')
    @include('admin.script.publication-data')
    @include('admin.script.employee-data')
    @include('admin.script.archive-data')
@endsection

@section('script-body')
    <script>
        @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Civil::class))
            _navcard.render({
                element:'nav-info',
                items: [
                    {id:'jumlah_dusun',value:_districts._len,label:'jumlah dusun', icon:'bar-chart'},
                    {id:'jumlah_rt',value:_neighboors._len,label:'jumlah RT', icon:'bar-chart'},
                    {id:'jumlah_keluarga',value:_families._len,label:'jumlah keluarga', icon:'home'},
                    {id:'jumlah_penduduk',value:_citizens._len,label:'jumlah penduduk', icon:'users'},
                ],
            });
        @elseif(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Employee::class))
            _navcard.render({
                element:'nav-info',
                items: [
                    {id:'jumlah_pegawai',value:_officers._len,label:'jumlah pegawai', icon:'users'},
                    {id:'jumlah_mutasi_masuk',value:_mutIns._len,label:'mutasi masuk', icon:'corner-up-left'},
                    {id:'jumlah_mutasi_keluar',value:_mutOuts._len,label:'mutasi keluar', icon:'corner-up-right'},
                ],
            });
        @endif
        @if(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Employee::class))
            _card.render({
                element : 'table-civil',
                items : [{
                    title : '<a href="{{url('/citizens'.\App\Http\back\_UI::$FLAG_UI)}}" class="text-dark">Data Penduduk</a>',
                    label : 'ti-user',
                    content : _tables.render({
                        element : 'citizens-data',
                        column : [
                            {content : 'NIK'},
                            {content : 'Nama'},
                            {content : 'Status'},
                            {content : 'Pekerjaan'},
                            {content : 'Agama'},
                        ]
                    })
                }],
            });
        @endif
        @if(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Employee::class))
            _card.render({
                element : 'table-employee',
                items : [{
                    title : '<a href="{{url('/officers'.\App\Http\back\_UI::$FLAG_UI)}}" class="text-dark">Data Pegawai</a>',
                    label : 'ti-user',
                    content : _tables.render({
                        element : 'officers-data',
                        column : [
                            {content : 'Nama'},
                            {content : 'Status'},
                            {content : 'Pangkat Golongan'},
                            {content : 'Jabatan'},
                        ]
                    })
                }],
            });
        @endif
        @if(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Account::class))
            _card.render({
                element : 'table-account',
                items : [{
                title : '<a href="{{url('/roles'.\App\Http\back\_UI::$FLAG_UI)}}" class="text-dark">Data Akun</a>',
                label : 'ti-user',
                    content : _tables.render({
                        element : 'accounts-data',
                        column : [
                            {content : 'NIK'},
                            {content : 'Email'},
                            {content : 'Nama'},
                            {content : 'Tipe'},
                        ]
                    })
                }],
            });
        @endif
        @if(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Archive::class))
            _card.render({
                element : 'table-archive',
                items : [{
                    title : '<a href="{{url('/archives'.\App\Http\back\_UI::$FLAG_UI)}}" class="text-dark">Data Arsip Surat</a>',
                    label : 'ti-user',
                    content : _tables.render({
                        element : 'archives-data',
                        column : [
                            {content : 'Jenis Permohonan'},
                            {content : 'Token Akses'},
                            {content : 'Pemohon'},
                        ]
                    })
                }],
            });
        @endif
        @if(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Publication::class))
            _card.render({
                element : 'table-publication',
                items : [{
                    title : '<a href="{{url('/blogs'.\App\Http\back\_UI::$FLAG_UI)}}" class="text-dark">Data Blog Berita</a>',
                    label : 'ti-user',
                    content : _tables.render({
                        element : 'publications-data',
                        column : [
                            {content : 'Judul'},
                            {content : 'Penulis'},
                            {content : 'Tanggal'},
                        ]
                    })
                }],
            });
        @endif
    </script>
@endsection
