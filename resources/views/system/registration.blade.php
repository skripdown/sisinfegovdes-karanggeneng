@extends('root.template')


@section('title')
    Registrasi
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">
                Daftar
            </h2>
            <hr>
            <form id="form" data-form="1">
                <div class="form-group">
                    <h6 class="h6 text-muted mt-4">Nama Lengkap</h6>
                    <label for="ip-0" class="d-none"></label>
                    <input id="ip-0" type="text" name="name" class="form-control" placeholder="nama lengkap">
                    <h6 class="h6 text-muted mt-4">Nomor Induk Kependudukan</h6>
                    <label for="ip-1" class="d-none"></label>
                    <input id="ip-1" type="text" name="identity" class="form-control" placeholder="nomor induk kependudukan">
                    <h6 class="h6 text-muted mt-4">Jenis Kelamin</h6>
                    <div>
                        <div class="form-check form-check-inline mr-5">
                            <input class="form-check-input mr-4" type="radio" name="jenisKelamin" id="ip-2-1" value="laki-laki" checked>
                            <label class="form-check-label" for="ip-2-1">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline ml-5">
                            <input class="form-check-input mr-4" type="radio" name="jenisKelamin" id="ip-2-2" value="perempuan">
                            <label class="form-check-label" for="ip-2-2">Perempuan</label>
                        </div>
                    </div>
                    <h6 class="h6 text-muted mt-4">Tempat Tanggal Lahir</h6>
                    <div class="row">
                        <div class="col-2">
                            <label for="ip-3" class="d-none"></label>
                            <select name="" id="ip-3" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="ip-4" class="d-none"></label>
                            <select name="" id="ip-4" class="form-control">
                                <option value="1">januari</option>
                                <option value="2">februari</option>
                                <option value="3">maret</option>
                                <option value="4">april</option>
                                <option value="5">mei</option>
                                <option value="6">juni</option>
                                <option value="7">juli</option>
                                <option value="8">agustus</option>
                                <option value="9">september</option>
                                <option value="10">oktober</option>
                                <option value="11">november</option>
                                <option value="12">desember</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="ip-5" class="d-none"></label>
                            <input id="ip-5" type="text" class="form-control" placeholder="tahun">
                        </div>
                        <div class="col-5">
                            <label for="ip-6" class="d-none"></label>
                            <input id="ip-6" type="text" class="form-control" placeholder="tempat">
                        </div>
                    </div>
                    <h6 class="h6 text-muted mt-4">Alamat E-mail</h6>
                    <label for="ip-19" class="d-none"></label>
                    <input id="ip-19" type="email" name="identity" class="form-control" placeholder="alamat email">
                    <h6 class="h6 text-muted mt-4">Nomor HP</h6>
                    <label for="ip-22" class="d-none"></label>
                    <input id="ip-22" type="email" name="identity" class="form-control" placeholder="nomor hp">
                    <h6 class="h6 text-muted mt-4">Kata Sandi</h6>
                    <label id="pass-label" for="ip-20" class="d-none"></label>
                    <input id="ip-20" type="password" name="identity" class="form-control" placeholder="kata sandi">
                    <h6 class="h6 text-muted mt-4">Verifikasi Kata Sandi</h6>
                    <label for="ip-21" class="d-none"></label>
                    <input id="ip-21" type="password" name="identity" class="form-control" placeholder="verifikasi kata sandi">
                    <h6 class="card-subtitle mt-4">berkas scan Foto KTP dalam bentuk <code>.jpg</code> , <code>.jpeg</code> atau <code>.png</code></h6>
                    <div class="custom-file">
                        <input type="file" name="pic" class="custom-file-input" id="ip-18">
                        <label class="custom-file-label text-muted" for="ip-18">foto ktp</label>
                    </div>
                </div>
                <div class="form-check form-check-inline mt-4 pt-2">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="setuju">
                        <label class="custom-control-label" for="setuju">Saya setuju mendaftarkan diri saya pada Sistem Informasi {{env('APP_NAME').' '.env('APP_DESCRIPTION')}}.</label>
                    </div>
                </div>
                <div class="form-group text-right mt-4">
                    <button id="submit-form" class="btn btn-success" type="button">daftar</button>
                </div>
            </form>
        </div>
    </div>
    <div id="tagger"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
    </script>
@endsection

@section('script-body')
    <script>
        _tagger.content = document.getElementById('tagger');
        const form   = document.getElementById('form');
        const submit = document.getElementById('submit-form');
        collections.set({
            name : 'form-data',
            submit : 'submit-form',
            fields : [
                {el: 'ip-0', name: 'name', validator: 'name'},
                {el: 'ip-1', name: 'nid', validator: 'number'},
                {el: ['ip-2-1', 'ip-2-2'], name: 'gender'},
                {el: 'ip-3', name: 'day_birth',},
                {el: 'ip-4', name: 'month_birth',},
                {el: 'ip-5', name: 'year_birth', validator: 'year'},
                {el: 'ip-6', name: 'place_birth',},
                {el: 'ip-19', name: 'email', validator: 'email'},
                {el: 'ip-20', name: 'password',},
                {el: 'ip-21', name: 'verify',},
                {el: 'ip-22', name: 'phone', validator: 'phone'},
                {el: 'ip-18', name: 'id_pic', validator: 'noEmpty'},
                {el: 'setuju', name: 'setuju', validator: 'check'},
            ],
        });
        _passmatch.for({
            source : 'ip-20',
            target : 'ip-21',
            label  : 'pass-label',
        });
        submit.addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-data');
            _response.post({async:false, url:'{{url('registrationInsert')}}', data: data[0], file:data[1]});
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
                                    window.location.href = '{{url('/')}}';
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
            }
            else {
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
        _tagger.tag('popup-notification');
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
