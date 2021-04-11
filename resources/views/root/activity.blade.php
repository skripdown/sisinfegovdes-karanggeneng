@if (\App\Http\back\_Authorize::admin())
    @extends('admin.template')
@else
    @extends('citizen.template')
@endif

@section('title')
    Aktivitas saya
@endsection

@section('sidebar-extension')
    <li class="sidebar-item">
        <a href="{{route('my_activities')}}" class="sidebar-link">
            <i data-feather="bar-chart" class="feather-icon"></i>
            <span class="hide-menu">Aktivitas saya</span>
        </a>
    </li>
@endsection

@section('page-breadcrumb')
    Riwayat aktivitas akun saya
@endsection

@section('sub-breadcrumb')
    Halaman Informasi Riwayat Aktivitas Penggunaan Akun {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="card-activity"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const activities = {!! $data !!};
    </script>
@endsection

@section('script-body')
    <script>
        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'card-activity',
            items   : [
                {
                    title : 'Daftar Aktivitas',
                    label : 'ti-server',
                    id    : 'data-activities',
                    content : concat('div', [
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
                                        content : '<p>apakah anda yakin ingin menghapus semua aktivitas anda ?</p>',
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
                                                    _response.post({async:false, url:'{{url('clearMyActivity')}}', data:{id:'noid'}, file: new FormData()});
                                                    const res = _response.response;
                                                    let output;
                                                    if (res._status) {
                                                        document.getElementById('table-activities').firstChild.children[1].innerHTML = '';
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
                            element : 'table-activities',
                            template : 'custom',
                            column : [
                                {content : 'Aktivitas'},
                                {content : 'Waktu'},
                                {content : 'Aksi'},
                            ],
                        })
                    ])
                }
            ]
        });
        for (let i = activities.length -1; i >= 0; i--) {
            const activity = activities[i];
            if (activity.view_by_me) {
                const time     = _date.convert_created_at(activity.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>';
                _tables.insert({
                    element : 'table-activities',
                    column  : [
                        {content : '<span class="font-weight-medium">' + activity.activity + '</span>'},
                        {content : '<span class="font-weight-medium">' + time + '</span>'},
                        {
                            content : _delete.render(function (e) {
                                _transition.in();
                                _response.post({async:false, url:'{{url('activityDelete')}}', data:{id:activity.id}, file:new FormData()});
                                const res = _response.response;
                                const tar = e.target;
                                if (res._status) {
                                    if (res.status === 'success') {
                                        let node = tar;
                                        while (node.nodeName !== 'TR') {
                                            node = node.parentNode;
                                        }
                                        const parent = node.parentNode;
                                        parent.removeChild(node);
                                    } else {
                                        _popup.content({
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
                                        });
                                    }
                                } else {
                                    _popup.content({
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
                                    });
                                }
                                _transition.out();
                            })
                        },
                    ],
                });
            }
        }
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
