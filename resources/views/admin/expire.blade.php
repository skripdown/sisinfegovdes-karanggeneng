@extends('admin.template')

@section('title')
    Pensiun
@endsection

@section('page-breadcrumb')
    Data Pensiun
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Data Pensiun Kepegawaian {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-pensiun"></div>
    <div id="history-exp"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const pic_admin   = '{{asset(env('PATH_ADMIN_PROFILE'))}}/';
        const pic_user    = '{{asset(env('PATH_CITIZEN_PROFILE'))}}/';
        const init        = function () {
            const temp = {!! $data !!};
            const res  = {};

            for (let i = 0; i < temp.length; i++) {
                res[temp[i].user_target] = temp[i];
            }

            return res;
        };
        const _in_request = init();
        const _officers   = _data.officer;
        _officers.refresh(function () {
            _response.get('{{url('/officers'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(user,user.role,approvals,requestmutates)')}}', false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function rank(rank) {
            const rank_arr = {!! json_encode(\App\Models\Officer::$rank) !!};
            if (rank_arr[rank].set === undefined)
                return ['-', '-', '-'];
            return [rank, rank_arr[rank].set,rank_arr[rank].room];
        }
        function inRequest(officer) {
            return _in_request[officer.user.id] !== undefined;
        }
        function inActive(officer) {
            return officer.regis === 'in';
        }
        function officerSalary(salary) {
            salary = (salary + '').split('');
            let separated = 0;
            let result = [];
            for (let i = salary.length - 1; i >= 0; i--) {
                separated++;
                result.push(salary[i]);
                if (separated === 3) {
                    separated = 0;
                    result.push('.');
                }
            }
            result.reverse();

            return result.join('');
        }

        _card.render({
            element : 'data-pensiun',
            items : [
                {
                    title : 'Data Permintaan Pensiun',
                    label : 'ti-user',
                    id : 'data-pensiun',
                    content : _tables.render({
                        element : 'table-pensiun',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Jenis'},
                            {content : 'Pangkat/Jabatan'},
                            {content : 'Golongan'},
                            {content : 'Masa Kerja'},
                            {content : 'Waktu Pengajuan'},
                        ]
                    })
                },
                {
                    title : 'Data Pegawai Aktif',
                    label : 'ti-plus',
                    id : 'data-pegawai-aktif',
                    content : _tables.render({
                        element : 'table-pegawai',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Jenis'},
                            {content : 'Pangkat/Jabatan'},
                            {content : 'Golongan'},
                            {content : 'Gaji'},
                            {content : 'Aksi'},
                        ]
                    })
                }
            ]
        });
        _card.render({
            element : 'history-exp',
            items   : [
                {
                    title : 'Riwayat Pensiun',
                    label : 'ti-user',
                    id : 'data-pensiun',
                    content : _tables.render({
                        element : 'table-his-exp',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Jenis'},
                            {content : 'Pangkat/Jabatan'},
                            {content : 'Golongan'},
                            {content : 'Pensiun Pada'},
                        ]
                    })
                },
            ],
        });

        let node_iter = _officers._first;
        while (node_iter !== undefined) {
            const officer = node_iter;
            const roomSet = rank(officer.rank);
            if (inRequest(officer)) {
                const request = _in_request[officer.user.id];
                _tables.insert({
                    element : 'table-pensiun',
                    column  : [
                        {
                            content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_admin + officer.user.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+officer.user.name+'</h5><span class="text-muted font-14">'+officer.identity+'</span></div></div>',
                        },
                        {content : '<span class="font-weight-medium text-uppercase">' + officer.status + '</span>'},
                        {content : '<span class="font-weight-medium text-capitalize">' + roomSet[0] + '<small class="text-muted"> / </small><span class="text-uppercase">' + roomSet[1] + '</span></span>'},
                        {content : '<span class="font-weight-medium text-uppercase">' + roomSet[2] + '</span>'},
                        {content : '<span class="font-weight-medium">' + _date.timestamp_old(officer.created_at) + '</span>'},
                        {content : '<span class="font-weight-medium">' + _date.convert_created_at(request.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                    ],
                });
            } else if (inActive(officer)){
                if (officer.status === 'asn') {
                    _tables.insert({
                        element : 'table-pegawai',
                        column  : [
                            {
                                content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_admin + officer.user.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+officer.user.name+'</h5><span class="text-muted font-14">'+officer.identity+'</span></div></div>',
                            },
                            {content : '<span class="font-weight-medium text-uppercase">' + officer.status + '</span>'},
                            {content : '<span class="font-weight-medium text-capitalize">' + roomSet[0] + '<small class="text-muted"> / </small><span class="text-uppercase">' + roomSet[1] + '</span></span>'},
                            {content : '<span class="font-weight-medium text-uppercase">' + roomSet[2] + '</span>'},
                            {content : '<span class="font-weight-medium"><small class="text-muted">Rp.</small> ' + officerSalary(officer.salary) + '</span>'},
                            {
                                content : _btn_group.make([
                                    _btn.render({
                                        size : 'sm',
                                        operate : 'pensiun',
                                        type : 'success',
                                        title : 'pensiun',
                                        content : 'pensiun',
                                        fun : function (e) {
                                            _transition.in();
                                            _response.post({async:false, url:'{{url('mutate')}}', data:{id:officer.user.id, type:'exp'}, file:new FormData()});
                                            const res = _response.response;
                                            let output;
                                            if (res._status) {
                                                if (res.status === 'success') {
                                                    const mutate = res.requestmutate;
                                                    _tables.insert({
                                                        element : 'table-pensiun',
                                                        column  : [
                                                            {
                                                                content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_admin + officer.user.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+officer.user.name+'</h5><span class="text-muted font-14">'+officer.identity+'</span></div></div>',
                                                            },
                                                            {content : '<span class="font-weight-medium text-uppercase">' + officer.status + '</span>'},
                                                            {content : '<span class="font-weight-medium text-capitalize">' + roomSet[0] + '<small class="text-muted"> / </small><span class="text-uppercase">' + roomSet[1] + '</span></span>'},
                                                            {content : '<span class="font-weight-medium text-uppercase">' + roomSet[2] + '</span>'},
                                                            {content : '<span class="font-weight-medium">' + _date.timestamp_old(officer.created_at) + '</span>'},
                                                            {content : '<span class="font-weight-medium">' + _date.convert_created_at(mutate.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                                                        ],
                                                    });
                                                    let target = e.target;
                                                    while (target.nodeName !== 'TR') {
                                                        target = target.parentNode;
                                                    }
                                                    const par = target.parentNode;
                                                    par.removeChild(target);
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
                                        }
                                    }),
                                ]),
                            },
                        ],
                    });
                }
            } else if (officer.regis === 'exp'){
                _tables.insert({
                    element : 'table-his-exp',
                    column  : [
                        {
                            content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_admin + officer.user.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+officer.user.name+'</h5><span class="text-muted font-14">'+officer.identity+'</span></div></div>',
                        },
                        {content : '<span class="font-weight-medium text-uppercase">' + officer.status + '</span>'},
                        {content : '<span class="font-weight-medium text-capitalize">' + roomSet[0] + '<small class="text-muted"> / </small><span class="text-uppercase">' + roomSet[1] + '</span></span>'},
                        {content : '<span class="font-weight-medium text-uppercase">' + roomSet[2] + '</span>'},
                        {content : '<span class="font-weight-medium">' + _date.convert_created_at(officer.updated_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                    ],
                });
            }
            node_iter = node_iter._next;
        }
        _popup.init({element : 'popup-notification', align : 'center',});
        _transition.out();
    </script>
@endsection
