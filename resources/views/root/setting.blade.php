@if (\App\Http\back\_Authorize::admin())
    @extends('admin.template')
@else
    @extends('citizen.template')
@endif

@php
    $data        = null;
    $religions   = null;
    $educations  = null;
    $occupations = null;
    if (isset($conf)) {
        $data        = $conf->data;
        $religions   = $conf->religion;
        $educations  = $conf->education;
        $occupations = $conf->occupation;
    }
@endphp

@section('title')
    Pengaturan
@endsection

@section('sidebar-extension')
    <li class="sidebar-item">
        <a href="{{route('settings')}}" class="sidebar-link">
            <i data-feather="settings" class="feather-icon"></i>
            <span class="hide-menu">Pengaturan</span>
        </a>
    </li>
@endsection

@section('page-breadcrumb')
    Pengaturan Akun
@endsection

@section('sub-breadcrumb')
    Halaman Pengaturan Informasi Akun Pengguna {{env('APP_NAME')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-2 d-sm-none d-lg-inline-block"></div>
        <div class="col-xl-5 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form id="form">
                        <div class="form-group">
                            <h6 class="h6 text-muted mt-4">Nama Lengkap</h6>
                            <label for="ip-0" class="d-none"></label>
                            <input id="ip-0" type="text" name="name" class="form-control" placeholder="nama lengkap" value="{{$data->name}}">
                            <h6 class="h6 text-muted mt-4">Status Perkawinan</h6>
                            <div>
                                <div class="form-check form-check-inline mr-5">
                                    <input class="form-check-input mr-4" type="radio" name="statusPerkawinan" id="ip-1-1" value="sudah" @if($data->citizen->marriage) checked @endif ><label class="form-check-label" for="ip-1-1">Menikah</label>
                                </div>
                                <div class="form-check form-check-inline ml-5">
                                    <input class="form-check-input mr-4" type="radio" name="statusPerkawinan" id="ip-1-2" value="belum" @if(!$data->citizen->marriage) checked @endif><label class="form-check-label" for="ip-1-2">Belum Menikah</label>
                                </div>
                            </div>
                            <h6 class="h6 text-muted mt-4">Golongan Darah</h6>
                            <label for="ip-2" class="d-none"></label>
                            <select name="" id="ip-2" class="form-control">
                                <option value="a+" @if($data->citizen->blood == 'a+') selected @endif>a+</option>
                                <option value="a-" @if($data->citizen->blood == 'a-') selected @endif>a-</option>
                                <option value="b+" @if($data->citizen->blood == 'b+') selected @endif>b+</option>
                                <option value="b-" @if($data->citizen->blood == 'b-') selected @endif>b-</option>
                                <option value="ab+" @if($data->citizen->blood == 'ab+') selected @endif>ab+</option>
                                <option value="ab-" @if($data->citizen->blood == 'ab-') selected @endif>ab-</option>
                                <option value="o+" @if($data->citizen->blood == 'o+') selected @endif>o+</option>
                                <option value="o-" @if($data->citizen->blood == 'o-') selected @endif>o-</option>
                            </select>
                            <h6 class="h6 text-muted mt-4">Agama</h6>
                            <label for="ip-3" class="d-none"></label>
                            <select name="" id="ip-3" class="form-control">
                                @foreach($religions as $religion)
                                    <option value="{{$religion->id}}" @if($data->citizen->citreligion->religion_id == $religion->id) selected @endif>{{$religion->name}}</option>
                                @endforeach
                            </select>
                            <h6 class="h6 text-muted mt-4">Pendidikan</h6>
                            <label for="ip-4" class="d-none"></label>
                            <select name="" id="ip-4" class="form-control">
                                @foreach($educations as $education)
                                    <option value="{{$education->id}}" @if($data->citizen->citeducation->education_id == $education->id) selected @endif>{{$education->name}}</option>
                                @endforeach
                            </select>
                            <h6 class="h6 text-muted mt-4">Pekerjaan</h6>
                            <label for="ip-5" class="d-none"></label>
                            <select name="" id="ip-5" class="form-control">
                                @foreach($occupations as $occupation)
                                    <option value="{{$occupation->id}}" @if($data->citizen->citoccupation->occupation_id == $occupation->id) selected @endif>{{$occupation->name}}</option>
                                @endforeach
                            </select>
                            <h6 class="h6 text-muted mt-4">Dusun / RT / RW</h6>
                            <div class="row">
                                <div class="col-4">
                                    <label for="ip-6" class="d-none"></label>
                                    <select name="" id="ip-6" class="form-control">
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="ip-7" class="d-none"></label>
                                    <select name="" id="ip-7" class="form-control">
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="ip-8" class="d-none"></label>
                                    <select name="" id="ip-8" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <h6 class="h6 text-muted mt-4">Nomor Kartu Keluarga</h6>
                            <label for="ip-9" class="d-none"></label>
                            <input id="ip-9" type="text" name="" class="form-control mb-4" placeholder="nomor kartu keluarga" value="{{$data->citizen->family->number}}">
                            <hr>
                            <h6 class="h6 text-muted mt-4">Alamat E-mail</h6>
                            <label for="ip-10" class="d-none"></label>
                            <input id="ip-10" type="email" name="identity" class="form-control" placeholder="alamat email" value="{{$data->email}}">
                            <h6 class="h6 text-muted mt-4">Nomor HP</h6>
                            <label for="ip-11" class="d-none"></label>
                            <input id="ip-11" type="text" name="identity" class="form-control" placeholder="nomor hp" value="{{$data->phone}}">
                            <h6 class="card-subtitle mt-4">berkas Foto Profil dalam bentuk <code>.jpg</code> , <code>.jpeg</code> atau <code>.png</code></h6>
                            <div class="custom-file mb-4">
                                <input type="file" name="pic" class="custom-file-input" id="ip-12">
                                <label class="custom-file-label text-muted" for="ip-12">foto profil</label>
                            </div>
                            <hr>
                            <h6 class="h6 text-muted mt-4">Kata Sandi Baru</h6>
                            <label id="pass-label" for="ip-13" class="d-none"></label>
                            <input id="ip-13" type="password" name="identity" class="form-control" placeholder="kata sandi baru">
                            <h6 class="h6 text-muted mt-4">Verifikasi Kata Sandi Baru</h6>
                            <label for="ip-14" class="d-none"></label>
                            <input id="ip-14" type="password" name="identity" class="form-control" placeholder="verifikasi kata sandi">
                            <hr>
                            <h6 class="card-subtitle mt-4">Masukkan <code>kata sandi</code> sekarang untuk merubah data.</h6>
                            <label id="pass-label" for="ip-15" class="d-none"></label>
                            <input id="ip-15" type="password" name="identity" class="form-control" placeholder="kata sandi">
                            <div class="form-group text-right mt-4 pt-4">
                                <button id="submit-form" class="btn btn-success" type="button">simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-2 d-sm-none d-lg-inline-block"></div>
    </div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_district_input.js')}}"></script>
    <script>
        @include('root.token')
        const district = _data.district;
        district.refresh(function () {
            _response.get('{{url('/districts'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION)}}(hamlets,hamlets.neighboors)',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        const submit = document.getElementById('submit-form');
        _district_input.make({
            districts    : district,
            sel_district : 'ip-6',
            sel_hamlet   : 'ip-7',
            sel_neighbor : 'ip-8',
        })
        .set({{$data->citizen->district->id}}, {{$data->citizen->hamlet->id}}, {{$data->citizen->neighboor->id}});
        collections.set({
            name : 'form-data',
            submit : 'submit-form',
            fields : [
                {el: 'ip-0', name : 'name', validator: 'name', hasVal: true},
                {el: ['ip-1-1', 'ip-1-2'], name : 'marriage'},
                {el: 'ip-2', name : 'blood'},
                {el: 'ip-3', name : 'religion'},
                {el: 'ip-4', name : 'education'},
                {el: 'ip-5', name : 'occupation'},
                {el: 'ip-6', name : 'district'},
                {el: 'ip-7', name : 'hamlet'},
                {el: 'ip-8', name : 'neighbor'},
                {el: 'ip-9', name : 'family', validator: 'number', hasVal: true},
                {el: 'ip-10', name : 'email', validator: 'email', hasVal: true},
                {el: 'ip-11', name : 'phone', validator: 'phone', hasVal: true},
                {el: 'ip-12', name : 'profile'},
                {el: 'ip-13', name : 'new_password'},
                {el: 'ip-14', name : 'verify'},
                {el: 'ip-15', name : 'old_password', validator: 'noEmpty'},
            ]
        });
        _passmatch.for({
            source : 'ip-13',
            target : 'ip-14',
            label  : 'pass-label'
        });
        submit.addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-data');
            _response.post({async:false, url:'{{url('userUpdate')}}', data: data[0], file:data[1]});
            let output;
            if (_response.response._status) {
                if (_response.response.status === 'success') {
                    output = {
                        id : 'popup-notification',
                        header : '<strong>notifikasi sukses</strong>',
                        content : '<p>'+_response.response.message+'</p>',
                        footer : _btn_group.make([
                            _btn.render({
                                operate : 'tutup',
                                type : 'success',
                                title : 'tutup',
                                content : 'tutup',
                                fun : function () {
                                    window.location.href = '{{url('/dashboard')}}';
                                }
                            }),
                        ]),
                    };
                } else {
                    output = {
                        id : 'popup-notification',
                        header : '<strong>notifikasi gagal</strong>',
                        content : '<p>'+_response.response.message+'</p>',
                        footer : _btn_group.make([
                            _btn.render({
                                operate : 'tutup',
                                type : 'secondary',
                                title : 'tutup',
                                content : 'tutup',
                                fun : function () {
                                    _popup.close('popup-notification');
                                }
                            }),
                        ]),
                    };
                }
            } else {
                output = {
                    id : 'popup-notification',
                    header : '<strong>notifikasi gagal</strong>',
                    content : '<p>Terjadi kesalahan dalam proses submit form pendaftaran.</p>',
                    footer : _btn_group.make([
                        _btn.render({
                            operate : 'tutup',
                            type : 'secondary',
                            title : 'tutup',
                            content : 'tutup',
                            fun : function () {
                                _popup.close('popup-notification');
                            }
                        }),
                    ]),
                };
            }
            _transition.out();
            _popup.content(output);
        });
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
