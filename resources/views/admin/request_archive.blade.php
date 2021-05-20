@extends('admin.template')

@section('title')
    Permohonan Arsip
@endsection

@section('page-breadcrumb')
    Kelola Permohonan Surat
@endsection

@section('sub-breadcrumb')
    Halaman Manajemen Surat Daring {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-request"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _request=_data.request;
        _request.refresh(function () {
            _response.get('{{url('/requestarchives'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(user,user.officer)')}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        let content;
        if (_request._len === 0) {
            content = document.createElement('div');
            content.setAttribute('class', 'text-center text-muted');
            content.innerHTML = '<div class="m-4 p-4">tidak ada permohonan surat atau arsip.</div>';
            content = [{
                title   : 'Data Permohonan',
                label   : 'ti-archive',
                id      : 'data-permohonan',
                content : content
            }];
        }
        else {
            content = [
                {
                    title : 'Data Permohonan',
                    label : 'ti-archive',
                    id : 'data-permohonan',
                    content : _tables.render({
                        element : 'request-data',
                        template : 'custom',
                        column : [
                            {content : 'Profil Pemohon'},
                            {content : 'Permohonan'},
                            {content : 'Tanggal'},
                            {content : 'Aksi'},
                        ]
                    })
                },
                {
                    title : 'Detail Permohonan',
                    label : 'ti-briefcase',
                    id : 'form-permohonan',
                    content : _ui_factory.__general.compact_els('div', [
                        _formfield.render({
                            element : 'form-request',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'id', type:'hidden'},
                                {name:'nama', type: 'text', placeholder: 'nama pemohon', readOnly: true, value:'nama pemohon',header: 'nama pemohon'},
                                {name:'jenis', type: 'text', placeholder: 'jenis permohonan', readOnly: true, value:'jenis permohonan',header: 'jenis dokumen'},
                                {name:'token', type:'text', placeholder: 'akses token', readOnly: true,header: 'akses token dokumen'},
                                {name:'berkas',type : 'file',placeholder:'berkas',header:'unggah berkas permohonan dalam bentuk <code>.docx</code> atau <code>.pdf</code>'},
                                {type:'submit',placeholder: 'simpan'}
                            ]
                        })[0]
                    ])
                }
            ]
        }
        _card.render({
            element : 'data-request',
            items : content
        });
        if (_request._len !== 0) {
            let node_iter = _request._first;
            while (node_iter !== undefined) {
                const request = node_iter;
                const user    = request.user;
                let pic       = user.pic;
                if (user.officer != null)
                    pic = '{{asset(env('PATH_ADMIN_PROFILE'))}}' + '/' + pic;
                pic = '{{asset(env('PATH_CITIZEN_PROFILE'))}}' + '/' + pic;
                console.log(pic);
                _tables.insert({
                    element : 'request-data',
                    column  : [
                        {
                            content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="' + pic + '" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+user.name+'</h5><span class="text-muted font-14">'+user.identity+'</span></div></div>',
                            click  : function (e) {
                                window.focus_tar = e.target;
                                document.getElementById('form-request-ip-0').value = request.id + '';
                                document.getElementById('form-request-ip-1').value = request.user.name;
                                document.getElementById('form-request-ip-2').value = request.type;
                                document.getElementById('form-request-ip-3').value = request.token;
                                _card.show('form-permohonan');
                            },
                            dblclick : function () {
                                _card.focus('form-permohonan');
                            }
                        },
                        {content : '<span class="font-weight-medium">' + request.name + '</span>'},
                        {content : '<span class="font-weight-medium">' + _date.convert_created_at(request.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                        {
                            content : _btn_group.make([
                                _btn.render({
                                    size : 'sm',
                                    operate : 'proses',
                                    type : 'success',
                                    title : 'proses permintaan',
                                    content : '<span class="text-white"><i class="ti-check font-weight-medium"></i></span>',
                                    fun : function (e) {
                                        window.focus_tar = e.target;
                                        document.getElementById('form-request-ip-0').value = request.id + '';
                                        document.getElementById('form-request-ip-1').value = request.user.name;
                                        document.getElementById('form-request-ip-2').value = request.type;
                                        document.getElementById('form-request-ip-3').value = request.token;
                                        _card.show('form-permohonan');
                                        _card.focus('form-permohonan');
                                    }
                                }),
                                _delete.render(function (e) {
                                    _transition.in();
                                    _response.post({async:false, url:'{{url('reqarchiveDelete')}}', data:{id:request.id}, file:new FormData()});
                                    const res = _response.response;
                                    let output;
                                    if (res._status) {
                                        if (res.status === 'success') {
                                            let tr = e.target;
                                            while (tr.nodeName !== 'TR') {tr = tr.parentNode;}
                                            const par = tr.parentNode;
                                            par.removeChild(tr);
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
                                }),
                            ]),
                        }
                    ],
                });
                node_iter = node_iter._next;
            }
            collections.set({
                name   : 'form-request',
                submit : 'form-request-ip-5-submit',
                fields : [
                    {el: 'form-request-ip-0', name: 'id', hasVal:true},
                    {el: 'form-request-ip-4', name: 'file',},
                ],
            });
            document.getElementById('form-request-ip-5-submit').addEventListener('click', function () {
                _transition.in();
                const data = collections.collect('form-request');
                _response.post({async:false, url:'{{url('reqarchiveUpload')}}', data:data[0], file:data[1]});
                const res = _response.response;
                let output;
                if (res._status) {
                    if (res.status === 'success') {
                        let   tar = window.focus_tar;
                        while (tar.nodeName !== 'TR') {tar = tar.parentNode;}
                        const par = tar.parentNode;
                        par.removeChild(tar);
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
                _card.focus('data-permohonan');
                _card.hide('form-permohonan');
                _popup.content(output);
                _transition.out();
            });
            _card.hide('form-permohonan');
            _popup.init({element : 'popup-notification', align : 'center',});
        }
        _transition.out();
    </script>
@endsection
