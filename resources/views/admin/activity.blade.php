@extends('admin.template')

@section('title')
    Aktivitas
@endsection

@section('page-breadcrumb')
    Aktivitas Pengguna Sistem
@endsection

@section('sub-breadcrumb')
    Halaman Informasi Aktivitas Pengguna Sistem {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="card-users"></div>
    <div id="card-detail"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _data = {!! $data !!};
        window.click_table = undefined;
    </script>
@endsection

@section('script-body')
    <script>
        function lastActive(activity) {
           let day = _date.timestamp_old(activity.created_at);
           if (day === 'sehari')
               return 'hari ini';
           return day + ' yang lalu';
        }
        function authority(user) {
            if (user.officer != null)
                return 'Pegawai';
            else
                return 'Penduduk';
        }
        function setPhoto(img_path) {
            const pic_admin = '{{asset(env('PATH_ADMIN_PROFILE'))}}/';
            const pic_user  = '{{asset(env('PATH_CITIZEN_PROFILE'))}}/';
            const http = new XMLHttpRequest();
            http.open('HEAD', pic_admin + img_path, false);
            http.send();
            if (http.status !== 404)
                return pic_admin + img_path;
            return pic_user + img_path;
        }
        function activityContent(data) {
            const id    = 'activity-' + data.id;
            const table = _tables.render({
                element : id,
                template : 'custom',
                column : [
                    {content : 'aktivitas'},
                    {content : 'waktu'},
                ]
            });
            let count  = 0;
            let last   = undefined;

            for (let i = 0; i < data.length; i++) {
                const activity = data[i];
                const time     = _date.convert_created_at(activity.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>';
                if (activity.view_by_dev) {
                    _tables.insert({
                        element : id,
                        column  : [
                            {content : '<span class="font-weight-medium">' + activity.activity + '</span>'},
                            {content : '<span class="font-weight-medium">' + time + '</span>'},
                        ]
                    });
                    count++;
                    last = data[i];
                }
            }

            return {table:table, amount:count, last:last, tableId:id};
        }
        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'card-users',
            items   : [
                {
                    title : 'Daftar Pengguna',
                    label : 'ti-users',
                    id    : 'data-users',
                    content : concat('div',[
                        _btn_group.make([
                            _btn.render({
                                size : 'sm',
                                operate : 'clear',
                                type : 'success',
                                title : 'hapus semua riwayat aktivitas',
                                content : 'hapus semua <i class="ti-trash"></i>',
                                fun : function () {
                                    _popup.content({
                                        id : 'popup-notification',
                                        header  : '<strong>hapus data</strong>',
                                        content : '<p>apakah anda yakin ingin menghapus riwayat aktivitas semua pengguna pada sistem ?</p>',
                                        footer  : _btn_group.make([
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
                                                    _popup.close('popup-delete-record');
                                                    _transition.in();
                                                    _response.post({async:false, url:'{{url('clearDevActivity')}}', data:{id:'noid'}, file: new FormData()});
                                                    const res = _response.response;
                                                    let output;
                                                    if (res._status) {
                                                        document.getElementById('table-users').firstChild.children[1].innerHTML = '';
                                                        if (window.click_table !== undefined)
                                                            document.getElementById(window.click_table).firstChild.children[1].innerHTML = '';
                                                        if (res.status === 'success') {
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
                                                                            _popup.close('popup-notification');
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
                                        ])
                                    });
                                }
                            })
                        ]),
                        _tables.render({
                            element : 'table-users',
                            template : 'custom',
                            column : [
                                {content : 'Pengguna'},
                                {content : 'Otorisasi'},
                                {content : 'Aktivitas Terakhir'},
                                {content : 'Jumlah Aktivitas'},
                            ],
                        })
                    ])
                }
            ]
        });
        _card.render({
            element : 'card-detail',
            items   : [
                {
                    title : 'Riwayat Aktivitas',
                    label : 'ti-users',
                    id    : 'data-activities',
                    content : _tables.render({
                        element : 'table-activities',
                        template : 'custom',
                        column : [
                            {content : 'Aktivitas'},
                            {content : 'Waktu'},
                        ],
                    })
                }
            ]
        });
        let last_item;
        for (let i = 0; i < _data.length; i++) {
            const user    = _data[i];
            const img     = setPhoto(user.pic);
            const auth    = authority(user);
            const content = activityContent(user.activities);
            if (content.amount > 0) {
                _tables.insert({
                    element : 'table-users',
                    column  : [
                        {
                            content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+img+'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+user.name+'</h5><span class="text-muted font-14">'+user.identity+'</span></div></div>',
                            click  : function () {
                                _card.content('data-activities', content.table);
                                window.click_table = content.tableId;
                            }
                        },
                        {content : '<span class="font-weight-medium">' + auth + '</span>'},
                        {content : '<span class="font-weight-medium">' + lastActive(content.last) + '</span>'},
                        {content : '<span class="font-weight-medium">' + content.amount + ' <small class="text-muted">aktivitas</small></span>'},
                    ],
                });
                last_item = content;
                window.click_table = content.tableId;
            }
        }
        if (last_item !== undefined)
            _card.content('data-activities', last_item.table);
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
