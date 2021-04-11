@extends('admin.template')

@section('title')
    Log Sistem
@endsection

@section('page-breadcrumb')
    Log Informasi Sistem
@endsection

@section('sub-breadcrumb')
    Halaman Log Informasi Sistem {{env('APP_NAME')}}
@endsection

@section('content')
    <div class="row mb-5">
        <div class="col-6">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h3 class="card-title">Informasi Perangkat Lunak</h3>
                    <hr>
                    <div class="pt-1 pb-1">
                        <h3 class="card-title text-dark">{{env('APP_NAME')}}</h3>
                        <h6 class="card-subtitle">{{env('APP_SUBTITLE')}}</h6>
                    </div>
                    <table>
                        <tbody>
                        <tr>
                            <td><span class="font-weight-medium text-muted">Nama Sistem</span></td>
                            <td><span class="font-weight-bold text-muted">&nbsp; : &nbsp;</span></td>
                            <td><span id="info-app-name" class="font-weight-medium">Sisegov</span></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><span class="font-weight-medium text-muted">Host</span></td>
                            <td><span class="font-weight-bold text-muted">&nbsp; : &nbsp;</span></td>
                            <td><span id="info-app-host" class="font-weight-medium">Sisegov</span></td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-medium text-muted">Tahun / Versi</span></td>
                            <td><span class="font-weight-bold text-muted">&nbsp; : &nbsp;</span></td>
                            <td><span id="info-app-date" class="font-weight-medium">Sisegov</span></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><span class="font-weight-medium text-muted">Layanan DB</span></td>
                            <td><span class="font-weight-bold text-muted">&nbsp; : &nbsp;</span></td>
                            <td><span id="info-app-db" class="font-weight-medium">Sisegov</span></td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-medium text-muted">Developer</span></td>
                            <td><span class="font-weight-bold text-muted">&nbsp; : &nbsp;</span></td>
                            <td><span id="info-app-developer" class="font-weight-medium">Sisegov</span></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><span class="font-weight-medium text-muted">Framework</span></td>
                            <td><span class="font-weight-bold text-muted">&nbsp; : &nbsp;</span></td>
                            <td><span id="info-app-framework" class="font-weight-medium">Sisegov</span></td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-medium text-muted">Kontak</span></td>
                            <td><span class="font-weight-bold text-muted">&nbsp; : &nbsp;</span></td>
                            <td><span id="info-app-contact" class="font-weight-medium">Sisegov</span></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h3 class="card-title">Log Sistem</h3>
                    <hr>
                    <div>
                        Sistem mulai di-deploy pada <span id="info-logs-date"><small>tanggal</small> <span class="font-weight-medium">12 Maret 2021</span> <small>pukul</small> <span class="font-weight-medium">12.00 WIB</span></span>.<br>
                        Diakses oleh <small id="info-logs-ip" class="font-weight-medium text-white bg-secondary p-1 rounded-0">20</small> alamat dengan rincian <small  id="info-logs-account" class="font-weight-medium text-white bg-secondary p-1 rounded-0">20</small> akun,
                        <small id="info-logs-device" class="font-weight-medium text-white bg-secondary p-1 rounded-0">20</small> jenis perangkat, <small id="info-logs-browser" class="font-weight-medium text-white bg-secondary p-1 rounded-0">20</small> jenis perangkat lunak untuk mengakses,
                        <small id="info-logs-os" class="font-weight-medium text-white bg-secondary p-1 rounded-0">20</small> jenis sistem operasi.
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div style="margin: 0 0 0.25rem"><small id="info-logs-process" class="font-weight-medium text-dark bg-white p-1 rounded-0">20</small> proses pada sistem.</div>
                            <div style="margin: 0.25rem 0"><small id="info-logs-error" class="font-weight-medium text-white bg-danger p-1 rounded-0">20</small> error berhasil ditangkap.</div>
                            <div style="margin: 0.25rem 0"><small id="info-logs-warning" class="font-weight-medium text-white bg-warning p-1 rounded-0">20</small> peringatan berhasil ditangkap.</div>
                            <div style="margin: 0.25rem 0 0"><small id="info-logs-success" class="font-weight-medium text-white bg-success p-1 rounded-0">20</small> proses berhasil.</div>
                        </div>
                        <div class="col-6">
                            <div class="text-right h-100 d-flex flex-column">
                                <div class="mt-auto">
                                    <button id="btn-print" class="btn rounded-0 btn-success" title="cetak laporan"><i class="ti-printer"></i></button>
                                    <button id="btn-clear" class="btn rounded-0 btn-secondary" title="bersihkan log"><i class="ti-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="card-log"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('style-head')
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _webInfo = {
            app_name      : '{{env('APP_NAME')}}',
            app_year      : '{{env('APP_YEAR')}}',
            app_version   : '{{env('APP_VERSION')}}',
            app_status    : '{{env('APP_DEV_STATUS')}}',
            app_host      : '{{env('APP_HOST')}}',
            app_db        : '{{env('DB_CONNECTION')}}',
            app_framework : [
                {
                    name    : 'Laravel',
                    version : '8.3',
                },
                {
                    name    : 'Skripdown',
                    version : '3.0',
                },
            ],
            app_developer  : {
                name           : 'Malik Fajar Lapele',
                instagram_url  : '{{env('APP_AUTHOR_INFO')}}',
                instagram_name : '{{env('APP_AUTHOR')}}',
            }
        };
        const _ips = {!! \App\Http\back\_Log::IPs() !!};
        const _oses = {!! \App\Http\back\_Log::OSes() !!};
        const _devices = {!! \App\Http\back\_Log::devices() !!};
        const _browsers = {!! \App\Http\back\_Log::browsers() !!};
        const _accounts = {!! \App\Http\back\_Log::accounts() !!};
        const _logs = {!! $data !!};
        let _logs_process = 0, _logs_warning = 0, _logs_danger = 0, _logs_success = 0;
    </script>
@endsection

@section('script-body')
    <script>
        function iterStatus(status) {
            if (status === 0)
                _logs_process++;
            else if (status === -2)
                _logs_danger++;
            else if (status === -1)
                _logs_warning++;
            else
                _logs_success++;
        }
        function isSYSTEM(str) {
            if (str === 'SYSTEM')
                return '<span class="font-weight-bold opacity-7 text-info"><small>SYSTEM</small></span>';
            return str;
        }
        function cutMsg(str, status) {
            let bg = '';
            let txt = ' text-dark';
            const real = str.replace(/"/g, '&quot;');
            if (status === -2)
                bg = 'bg-danger p-1';
            else if (status === -1)
                bg = 'bg-warning p-1';
            else if (status === 1)
                bg = 'bg-success p-1';
            if (status !== 0)
                txt = ' text-white';
            if (str.length > 40) {
                return '<span class="'+bg+txt+'" title="'+real+'">' + str.slice(0, 40) + '...</span>';
            }

            return '<span class="'+bg+txt+' text-white" title="'+real+'">'+str+'</span>';
        }
        function statusMsg(status) {
            if (status === -2)
                return '<span class="text-danger font-weight-medium">error <small class="opacity-7">'+status+'</small></span>';
            if (status === -1)
                return '<span class="text-warning font-weight-medium">warning <small class="opacity-7">'+status+'</small></span>';
            if (status === 0)
                return '<span class="text-secondary font-weight-medium">info <small class="opacity-7">'+status+'</small></span>';
            if (status === 1)
                return '<span class="text-success font-weight-medium">success <small class="opacity-7">'+status+'</small></span>';;
        }
        function init(info) {
            const app_name = document.getElementById('info-app-name');
            const app_host = document.getElementById('info-app-host');
            const app_date = document.getElementById('info-app-date');
            const app_db   = document.getElementById('info-app-db');
            const app_dev  = document.getElementById('info-app-developer');
            const app_fw   = document.getElementById('info-app-framework');
            const app_ctc  = document.getElementById('info-app-contact');
            const inf_ip   = document.getElementById('info-logs-ip');
            const inf_os   = document.getElementById('info-logs-os');
            const inf_dev  = document.getElementById('info-logs-device');
            const inf_acc  = document.getElementById('info-logs-account');
            const inf_brow = document.getElementById('info-logs-browser');
            const inf_date = document.getElementById('info-logs-date');

            app_name.innerHTML = info.app_name;
            app_host.innerHTML = info.app_host;
            app_date.innerHTML = info.app_year + ' <small class="text-muted">/</small> ' + info.app_version + ' <small class="text-secondary">' + info.app_status + '</small>';
            app_db.innerHTML   = info.app_db;
            app_dev.innerHTML  = info.app_developer.name;
            app_ctc.innerHTML  = '<a target="_blank" href="' + info.app_developer.instagram_url +'">' + info.app_developer.instagram_name + '</a>';
            app_fw.innerHTML   = 'Laravel <small>' + info.app_framework[0].version + '</small>' + ', Skripdown <small>' + info.app_framework[1].version + '</small>';

            inf_ip.innerHTML   = (_ips.length-1) + '';
            inf_os.innerHTML   = (_oses.length-1) + '';
            inf_dev.innerHTML  = (_devices.length-1) + '';
            inf_acc.innerHTML  = (_accounts.length-1) + '';
            inf_brow.innerHTML = (_browsers.length-1) + '';
            inf_date.innerHTML = _date.convert_created_at(_logs[0].created_at, ' <small class="font-weight-bold">WIB</small> ', ' <small>tanggal</small> ', ' <small>pukul</small> ')
        }
        init(_webInfo);

        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'card-log',
            items   : [
                {
                    title : 'Log Proses Sistem',
                    label : 'ti-server',
                    id    : 'data-logs',
                    content : _tables.render({
                        element : 'table-logs',
                        template : 'custom',
                        column : [
                            {content : 'Indeks'},
                            {content : 'Alamat'},
                            {content : 'Pengguna'},
                            {content : 'Proses'},
                            {content : 'Status'},
                            {content : 'Perangkat'},
                            {content : 'Mesin Pencari'},
                            {content : 'Sistem Operasi'},
                        ],
                    })
                }
            ]
        });

        let iter = 1;
        for (let i = _logs.length - 1; i >= 0; i--) {
            const log = _logs[i];
            const msg = cutMsg(log.message, log.status);
            iterStatus(log.status);
            _tables.insert({
                element : 'table-logs',
                column : [
                    {content : iter + ''},
                    {content : isSYSTEM(log.logip.ip.address)},
                    {content : isSYSTEM(log.client.name)},
                    {content : msg},
                    {content : statusMsg(log.status)},
                    {content : isSYSTEM(log.logdevice.device.name)},
                    {content : isSYSTEM(log.logbrowser.browser.name)},
                    {content : isSYSTEM(log.logoperatingsystem.operatingsystem.name)},
                ],
            });
            iter++;
        }
        document.getElementById('info-logs-process').innerHTML = _logs_process + '';
        document.getElementById('info-logs-error').innerHTML   = _logs_danger + '';
        document.getElementById('info-logs-warning').innerHTML = _logs_warning + '';
        document.getElementById('info-logs-success').innerHTML = _logs_success + '';
        document.getElementById('btn-clear').addEventListener('click', function () {
            _popup.content({
                id : 'popup-notification',
                header : '<strong>konfirmasi</strong>',
                content : '<p>Apakah anda yakin ingin membersihkan semua data log proses.</p>',
                footer : _btn_group.make([
                    _btn.render({
                        operate : 'batal',
                        type : 'success',
                        title : 'batal',
                        content : 'batal',
                        fun : function () {
                            _popup.close('popup-notification');
                        }
                    }),
                    _btn.render({
                        operate : 'hapus',
                        type : 'secondary',
                        title : 'hapus',
                        content : 'hapus',
                        fun : function () {
                            _popup.close('popup-notification');
                            console.log('ex deleted');
                            _transition.in();
                            _response.post({async:false,url:'{{url('logClear')}}',data:{id:'noid'},file:new FormData()});
                            let output;
                            const res = _response.response;
                            if (res._status) {
                                if (res.status === 'success') {
                                    location.reload(true);
                                    output = {
                                        id : 'popup-notification',
                                        header : '<strong>notifikasi sukses</strong>',
                                        content : '<p>' + _response.response.message + '.</p>',
                                        footer : _btn_group.make([
                                            _btn.render({
                                                operate : 'tutup',
                                                type : 'success',
                                                title : 'tutup',
                                                content : 'tutup',
                                                fun : function () {
                                                    console.log('ex final');
                                                    alert();
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
                        }
                    }),
                ]),
            });
        });
        _popup.init({element : 'popup-notification', align : 'center',});
        _transition.out();
    </script>
@endsection
