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
    $officer_disabled = '';
    $has_approval     = false;
    if (\App\Http\back\_Authorize::admin() || \App\Http\back\_Authorize::chief()) {
        if (\App\Models\Approval::hasApproval(\App\Http\back\_Authorize::data()->officer->id)) {
            $officer_disabled = ' disabled';
            $has_approval     = true;
        }
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
                    <div class="card-title">
                        <h3 class="font-weight-medium">Profil Biodata&nbsp;&nbsp;<button class="btn btn-outline-light btn-sm btn-min"><i class="ti-arrow-up"></i></button></h3>
                        <hr>
                    </div>
                    <div class="card-content">
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
                                <select name="" id="ip-5" class="form-control" @if(\App\Http\back\_Authorize::admin() || \App\Http\back\_Authorize::chief()) disabled @endif>
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
        </div>
        <div class="col-xl-3 col-md-2 d-sm-none d-lg-inline-block"></div>
    </div>
    @if (\App\Http\back\_Authorize::admin() || \App\Http\back\_Authorize::chief())
        <div class="row">
            <div class="col-xl-3 col-md-2 d-sm-none d-lg-inline-block"></div>
            <div class="col-xl-5 col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h3 class="font-weight-medium">Kepegawaian&nbsp;&nbsp;<button class="btn btn-outline-light btn-sm btn-min"><i class="ti-arrow-up"></i></button></h3>
                            <hr>
                        </div>
                        <div class="btn-content">
                            <form action="">
                                <h6 class="h6 text-muted mt-4">Mode Admin Kepegawaian</h6>
                                <div>
                                    <div class="form-check form-check-inline mr-5">
                                        <input class="form-check-input mr-4" type="radio" name="adm_mode" id="adm-mode-1" value="aktif" @if(\App\Http\back\_App::admin()) checked @endif ><label class="form-check-label" for="adm-mode-1">Aktif</label>
                                    </div>
                                    <div class="form-check form-check-inline ml-5">
                                        <input class="form-check-input mr-4" type="radio" name="adm_mode" id="adm-mode-2" value="tidak" @if(!\App\Http\back\_App::admin()) checked @endif><label class="form-check-label" for="adm-mode-2">Tidak Aktif</label>
                                    </div>
                                </div>
                                @if(\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Developer::class) || \App\Http\back\_Authorize::chief())
                                    <h6 class="h6 text-muted mt-4">Mode Developer</h6>
                                    <h6 class="h6 text-muted small"><code>Mode Developer masih dalam tahap pengembangan.</code></h6>
                                    <div>
                                        <div class="form-check form-check-inline mr-5">
                                            <input class="form-check-input mr-4" type="radio" name="dev_mode" id="dev-mode-1" value="aktif" @if(\App\Http\back\_App::developer()) checked @endif ><label class="form-check-label" for="dev-mode-1">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline ml-5">
                                            <input class="form-check-input mr-4" type="radio" name="dev_mode" id="dev-mode-2" value="tidak" @if(!\App\Http\back\_App::developer()) checked @endif><label class="form-check-label" for="dev-mode-2">Tidak Aktif</label>
                                        </div>
                                    </div>
                                @endif
                            </form>
                            <form action="" id="form-officer">
                                @if ($has_approval)
                                    <h6 class="h5 text-muted mt-4">Permintaan perubahan data sedang diproses oleh sistem...</h6>
                                @else
                                    <hr>
                                    <h6 class="h6 text-muted mt-4">Nomor Induk Pegawai</h6>
                                    <label for="ip-nip" class="d-none"></label>
                                    <input id="ip-nip" type="text" name="nip" class="form-control" placeholder="nip" value="{{$data->officer->identity}}"{{$officer_disabled}}>
                                    <h6 class="h6 text-muted mt-4 d-none">Jenis Pegawai</h6>
                                    <label for="ip-jenis" class="d-none"></label>
                                    <select name="" id="ip-jenis" class="form-control d-none"{{$officer_disabled}}>
                                        <option value="asn" @if($data->officer->status == 'asn') selected @endif>ASN</option>
                                        <option value="honor" @if($data->officer->status == 'honor') selected @endif>Honorer</option>
                                    </select>
                                    <h6 class="h6 text-muted mt-4 d-none">Pangkat Jabatan</h6>
                                    <label for="ip-pangkat" class="d-none"></label>
                                    <select name="" id="ip-pangkat" class="form-control d-none"{{$officer_disabled}}>
                                    </select>
                                    <input type="hidden" name="id" id="ip-id" value="{{$data->officer->id}}">
                                    <input type="hidden" name="gaji" id="ip-gaji" value="{{$data->officer->salary}}">
                                    @if (!\App\Http\back\_Authorize::chief())
                                        <h6 class="h6 text-muted mt-4">Pengubahan Status Kepegawaian</h6>
                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group mr-3" role="group" aria-label="group">
                                                <button id="submit-mutasi-keluar" type="button" class="btn btn-secondary btn-sm"{{$officer_disabled}}>Keluar</button>
                                            </div>
                                            <div class="btn-group mr-2" role="group" aria-label="Second group">
                                                <button id="submit-pensiun" type="button" class="btn btn-secondary btn-sm"{{$officer_disabled}}>Pensiun</button>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group text-right mt-4 pt-4">
                                        <button id="submit-form-officer" class="btn btn-success" type="button"{{$officer_disabled}}>simpan</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-2 d-sm-none d-lg-inline-block"></div>
        </div>
    @endif
    @if (\App\Http\back\_Authorize::chief())
        <div class="row">
            <div class="col-xl-3 col-md-2 d-sm-none d-lg-inline-block"></div>
            <div class="col-xl-5 col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h3 class="font-weight-medium">Kepala Desa&nbsp;&nbsp;<button class="btn btn-outline-light btn-sm btn-min"><i class="ti-arrow-up"></i></button></h3>
                            <hr>
                        </div>
                        <div class="card-content">
                            <form action="" id="form-chief">
                                @if ($has_approval)
                                    <h6 class="h5 text-muted mt-4">Permintaan sedang diproses oleh sistem...</h6>
                                @else
                                    <h6 class="h6 text-muted mt-4">Nomor Induk Pegawai Calon Kepada Desa</h6>
                                    <label for="ip-nip-calon" class="d-none"></label>
                                    <input id="ip-nip-calon" type="text" name="nip_calon" class="form-control" placeholder="nip calon" value=""{{$officer_disabled}}>
                                    <h6 class="card-subtitle mt-4">Masukkan <code>kata sandi</code> sebagai verifikasi.</h6>
                                    <label for="ip-verifikasi-kepala" class="d-none"></label>
                                    <input id="ip-verifikasi-kepala" type="password" name="verifikasi" class="form-control" placeholder="kata sandi verifikasi"{{$officer_disabled}}>
                                    <div class="form-check mt-4 pt-4">
                                        <input class="form-check-input" type="checkbox" value="" id="verifikasi-persetujuan-kepala-desa"{{$officer_disabled}}/>
                                        <label class="form-check-label text-muted h6" for="verifikasi-persetujuan-kepala-desa">
                                            Saya setuju untuk menggantikan jabatan saya sebagai kepala desa kepala pegawai dengan nomor induk <code id="verifikasi-nomor-induk"></code>
                                        </label>
                                    </div>
                                    <div class="form-group text-right mt-4">
                                        <button id="submit-form-kepala-desa" class="btn btn-success" type="button"{{$officer_disabled}}>simpan</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-2 d-sm-none d-lg-inline-block"></div>
        </div>
    @endif
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_district_input.js')}}"></script>
    <script>
        @include('root.token')
        @if (\App\Http\back\_Authorize::admin() || \App\Http\back\_Authorize::chief())
            const uppercase  = function (text) {
            const words = text.split(' ');
            let result  = '';
            for (let i = 0; i < words.length; i++) {
                result += (words[i][0].toUpperCase() + words[i].substring(1));
                if (i !== words.length - 1)
                    result += ' ';
            }

            return result;
        }
            const _ASN       = {
            asn     : undefined,
            honorer : undefined,
            init    : function () {
                function makeOption(options) {
                    const arr = [];
                    for (let i = 0; i < options.length; i++) {
                        const option = document.createElement('option');
                        const value  = options[i];
                        const text   = uppercase(options[i]);
                        option.setAttribute('value', value);
                        option.innerHTML = text;
                        arr.push(option);
                    }

                    return arr;
                }
                function makeGroup(name, options) {
                    const group = document.createElement('optgroup');
                    group.setAttribute('label', uppercase(name));
                    for (let i = 0; i < options.length; i++) {
                        group.appendChild(options[i]);
                    }

                    return group;
                }
                _ASN.asn = [
                    makeGroup('gol IV', makeOption(['pembina utama', 'pembina utama madya', 'pembina utama muda', 'pembina tingkat i', 'pembina'])),
                    makeGroup('gol III', makeOption(['penata tingkat i', 'penata', 'penata muda tingkat i', 'penata muda'])),
                    makeGroup('gol II', makeOption(['pengatur tingkat i', 'pengatur', 'pengatur muda tingkat i', 'pengatur muda'])),
                    makeGroup('gol I', makeOption(['juru tingkat i', 'juru', 'juru muda tingkat i', 'juru muda'])),
                ];
                _ASN.honorer = makeOption(['tidak ada'])[0];
            }
        };
            _ASN.init();
            function asnRankInput(select, active=false) {
            if (typeof select === 'string')
                select = document.getElementById(select);
            select.innerHTML = '';
            if (active) {
                const groups = _ASN.asn;
                for (let i = 0; i < groups.length; i++) {
                    select.appendChild(groups[i]);
                }
            }
            else {
                select.appendChild(_ASN.honorer);
            }
        }
        @endif
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
    @if (\App\Http\back\_Authorize::admin() || \App\Http\back\_Authorize::chief())
        <script>
            function removeClass(element, toRemove) {
                return element.getAttribute('class').replace(toRemove, '');
            }
            function addClass(element, toAdd) {
                return element.getAttribute('class') + toAdd;
            }

            const switch_admin = ' admin-off', switch_dev = ' developer-off';

            const menu_admin  = document.getElementsByClassName('admin');
            const btn_admin_1 = document.getElementById('adm-mode-1');
            const btn_admin_0 = document.getElementById('adm-mode-2');
            @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Developer::class) || \App\Http\back\_Authorize::chief())
                const menu_dev  = document.getElementsByClassName('developer')[0];
                const btn_dev_1 = document.getElementById('dev-mode-1');
                const btn_dev_0 = document.getElementById('dev-mode-2');
                @if (!\App\Http\back\_App::admin())
                    btn_dev_1.disabled = true;
                    btn_dev_0.disabled = true;
                @endif
            @endif
            btn_admin_1.addEventListener('click', function () {
                _response.post({async:true, url:'{{url('admin_on')}}', data:{i:0}, file:new FormData()});
                for (let i = 0; i < menu_admin.length; i++) {
                    const menu = menu_admin[i];
                    menu.setAttribute('class', removeClass(menu, switch_admin));
                }
                @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Developer::class) || \App\Http\back\_Authorize::chief())
                    btn_dev_1.disabled = false;
                    btn_dev_0.disabled = false;
                @endif
            });
            btn_admin_0.addEventListener('click', function () {
                _response.post({async:true, url:'{{url('admin_off')}}', data:{i:0}, file:new FormData()});
                _response.post({async:true, url:'{{url('dev_off')}}', data:{i:0}, file:new FormData()});
                for (let i = 0; i < menu_admin.length; i++) {
                    const menu = menu_admin[i];
                    menu.setAttribute('class', addClass(menu, switch_admin));
                }
                @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Developer::class) || \App\Http\back\_Authorize::chief())
                    btn_dev_1.disabled = true;
                    btn_dev_0.disabled = true;
                    btn_dev_0.checked  = true;
                @endif
            });
            @if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Developer::class) || \App\Http\back\_Authorize::chief())
                btn_dev_1.addEventListener('click', function () {
                    _response.post({async:true, url:'{{url('dev_on')}}', data:{i:0}, file:new FormData()});
                    menu_dev.setAttribute('class', removeClass(menu_dev, switch_dev));
                });
                btn_dev_0.addEventListener('click', function () {
                    _response.post({async:true, url:'{{url('dev_off')}}', data:{i:0}, file:new FormData()});
                    menu_dev.setAttribute('class', addClass(menu_dev, switch_dev));
                });
            @endif
        </script>
        <script>
            @if (!$has_approval)
                @if ($data->officer->status == 'asn')
                    asnRankInput(document.getElementById('ip-pangkat'), true);
                @else
                    asnRankInput(document.getElementById('ip-pangkat'), false);
                @endif
                document.getElementById('ip-jenis').addEventListener('change', function () {
                    const selSource = document.getElementById('ip-jenis');
                    const selTar    = document.getElementById('ip-pangkat');
                    if (selSource.value === 'asn')
                        asnRankInput(selTar, true);
                    else
                        asnRankInput(selTar, false);
                });
                collections.set({
                    name   : 'edit-officer',
                    submit : 'submit-form-officer',
                    fields : [
                        {el: 'ip-id', name: 'id', hasVal:true},
                        {el: 'ip-nip', name: 'identity', hasVal:true, validator:'number'},
                        {el: 'ip-jenis', name: 'status',},
                        {el: 'ip-pangkat', name: 'rank',},
                        {el: 'ip-gaji', name: 'salary', hasVal:true, validator:'number'},
                    ],
                });
                document.getElementById('submit-form-officer').addEventListener('click', function () {
                    _transition.in();
                    const data = collections.collect('edit-officer');
                    _response.post({async:false, url:'{{url('officerEdit')}}', data:data[0], file:data[1]});
                    const res = _response.response;
                    let output;
                    if (res._status) {
                        if (res.status === 'success') {
                            document.getElementById('form-officer').innerHTML = '<h6 class="h5 text-muted mt-4">Permintaan sedang diproses oleh sistem...</h6>';
                            @if (\App\Http\back\_Authorize::chief())
                                document.getElementById('form-chief').innerHTML = '<h6 class="h5 text-muted mt-4">Permintaan sedang diproses oleh sistem...</h6>';
                            @endif
                            output = {
                                id : 'popup-notification',
                                header : '<strong>notifikasi sukses</strong>',
                                content : '<p>' + res.message + '.</p>',
                                footer : _btn_group.make([
                                    _btn.render({
                                        operate : 'tutup',
                                        type : 'success',
                                        title : 'tutup',
                                        content : 'tutup',
                                        fun : function () {
                                            _popup.close('popup-notification');
                                        }
                                    }),
                                ]),
                            };
                        } else {
                            output = {
                                id : 'popup-notification',
                                header : '<strong>notifikasi gagal</strong>',
                                content : '<p>' + res.message + '.</p>',
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
                            content : '<p>Terjadi kesalahan dalam proses submit data.</p>',
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
                @if (\App\Http\back\_Authorize::chief())
                    collections.set({
                        name   : 'replace-chief',
                        submit : 'submit-form-kepala-desa',
                        fields : [
                            {el: 'ip-id', name: 'id', hasVal:true},
                            {el: 'ip-nip', name: 'identity', hasVal:true, validator:'number'},
                            {el: 'ip-jenis', name: 'status',},
                            {el: 'ip-pangkat', name: 'rank',},
                            {el: 'ip-gaji', name: 'salary', hasVal:true, validator:'number'},
                        ],
                    });
                @else
                    document.getElementById('submit-mutasi-keluar').addEventListener('click', function () {
                        _transition.in();
                        _response.post({async:false, url:'{{url('mutate')}}', data:{id:{{\App\Http\back\_Authorize::data()->officer->id}}, type:'out'}, file:new FormData()});
                        const res = _response.response;
                        let output;
                        if (res._status) {
                        if (res.status === 'success') {
                            document.getElementById('form-officer').innerHTML = '<h6 class="h5 text-muted mt-4">Permintaan sedang diproses oleh sistem...</h6>';
                            @if (\App\Http\back\_Authorize::chief())
                            document.getElementById('form-chief').innerHTML = '<h6 class="h5 text-muted mt-4">Permintaan sedang diproses oleh sistem...</h6>';
                            @endif
                                output = {
                                id : 'popup-notification',
                                header : '<strong>notifikasi sukses</strong>',
                                content : '<p>' + res.message + '.</p>',
                                footer : _btn_group.make([
                                    _btn.render({
                                        operate : 'tutup',
                                        type : 'success',
                                        title : 'tutup',
                                        content : 'tutup',
                                        fun : function () {
                                            _popup.close('popup-notification');
                                        }
                                    }),
                                ]),
                            };
                        } else {
                            output = {
                                id : 'popup-notification',
                                header : '<strong>notifikasi gagal</strong>',
                                content : '<p>' + res.message + '.</p>',
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
                            content : '<p>Terjadi kesalahan dalam proses submit data.</p>',
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
                    document.getElementById('submit-pensiun').addEventListener('click', function () {
                        _transition.in();
                        _response.post({async:false, url:'{{url('mutate')}}', data:{id:{{\App\Http\back\_Authorize::data()->officer->id}}, type:'exp'}, file:new FormData()});
                        const res = _response.response;
                        let output;
                        if (res._status) {
                    if (res.status === 'success') {
                        document.getElementById('form-officer').innerHTML = '<h6 class="h5 text-muted mt-4">Permintaan sedang diproses oleh sistem...</h6>';
                        @if (\App\Http\back\_Authorize::chief())
                        document.getElementById('form-chief').innerHTML = '<h6 class="h5 text-muted mt-4">Permintaan sedang diproses oleh sistem...</h6>';
                        @endif
                            output = {
                            id : 'popup-notification',
                            header : '<strong>notifikasi sukses</strong>',
                            content : '<p>' + res.message + '.</p>',
                            footer : _btn_group.make([
                                _btn.render({
                                    operate : 'tutup',
                                    type : 'success',
                                    title : 'tutup',
                                    content : 'tutup',
                                    fun : function () {
                                        _popup.close('popup-notification');
                                    }
                                }),
                            ]),
                        };
                    } else {
                        output = {
                            id : 'popup-notification',
                            header : '<strong>notifikasi gagal</strong>',
                            content : '<p>' + res.message + '.</p>',
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
                        content : '<p>Terjadi kesalahan dalam proses submit data.</p>',
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
                @endif
            @endif
        </script>
    @endif
    <script>
        const min_btns = document.getElementsByClassName('btn-min');
        for (let i = 0; i < min_btns.length; i++) {
            const btn  = min_btns[i];
            const icon = btn.firstChild;
            const tar  = btn.parentNode.parentNode.parentNode.children[1];
            btn.addEventListener('click', function () {
                if (icon.getAttribute('class') === 'ti-arrow-up') {
                    icon.setAttribute('class', 'ti-arrow-down');
                    tar.setAttribute('class', tar.getAttribute('class') + ' d-none');
                } else {
                    icon.setAttribute('class', 'ti-arrow-up');
                    tar.setAttribute('class', tar.getAttribute('class').replace(' d-none', ''));
                }
            });
            for (let i = 1; i < min_btns.length; i++) {
                min_btns[i].click();
            }
        }
    </script>
@endsection
