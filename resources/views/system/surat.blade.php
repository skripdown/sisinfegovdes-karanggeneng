@extends('system.template')


@section('title')
    Surat Elektronik
@endsection

@section('page-description')
    sistem informasi permohonan surat desa Karanggeneng kabupaten Lamongan
@endsection

@section('nav-title')
    SISPERS
@endsection

@section('app-nav')
@endsection

@section('page-title')
    Surat Elektronik
@endsection

@section('page-subtitle')
    Sistem Informasi Permohonan Surat
@endsection

@section('content-body')
    <div class="container pt-4 pb-4 mt-4 mb-4"></div>
    <div class="container-fluid mb-4 pb-4">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <h2 id="distrik" class="text-center">AKSES SURAT</h2>
                <hr>
                <div class="row" style="padding-top: 5rem; padding-bottom: 20rem">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <p class="small mt-5" style="">
                            Masukkan kode akses token untuk mendapatkan arsip surat dari server <a href="{{url('/away')}}" class="text-decoration-none text-danger"><code class="font-weight-bold">{{env('APP_NAME')}}</code></a>.
                        </p>
                        <form class="row">
                            <div class="col-12 col-sm pr-sm-0">
                                <input type="text" name="search" id="search" placeholder="Akses Token Surat" class="form-control">
                            </div>
                            <div class="col-12 col-sm-auto pl-sm-0">
                                <input type="button" name="commit" value="Buka" class="btn btn-success btn-block">
                            </div>
                            <div class="col-12 col-sm-auto pl-sm-0">
                                <input type="button" name="commit" value="Baru" class="btn btn-secondary btn-block">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
@endsection

@section('style-head')
@endsection

@section('script-head')
@endsection

@section('script-body')
@endsection
