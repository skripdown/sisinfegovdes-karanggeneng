@extends('admin.template')

@section('title')
    Arsip
@endsection

@section('page-breadcrumb')
    Kelola Arsip Surat
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Arsip Surat {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="archive-folder"></div>
    <div id="archive-detail"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _folder = _data.archive;
        _folder.refresh(function () {
            _response.get('{{url('/archives'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(archives,archives.archivefile,officer,officer.user)')}}',false);
            return _response.response;
        });
        const _files = {};
        const _empty = {};
        window.focus_key = undefined;
        window.focus_tar = undefined;
    </script>
@endsection

@section('script-body')
    <script>
        function makeTypeInput() {
            const row    = document.createElement('div');
            row.setAttribute('class', 'row mb-4');
            const el     = document.createElement('div');
            el.setAttribute('class', 'col-sm-12 col-md-8 col-lg-4 col-xl-3 mb-3 mt-1');
            el.innerHTML = '<small class="text-muted">buat folder arsip baru.</small><div class="input-group"><input type="text" id="form-jenis-arsip" class="form-control" placeholder="Jenis Arsip" aria-label="Recipients username" aria-describedby="basic-addon2"><div class="input-group-append"><button class="btn btn-success" id="btn-jenis-arsip" type="button">Tambah</button></div></div>';
            return el;
        }
        function makeFiles(folder) {
            function access_maker(file) {
                if (file.enable_public)
                    return '<span class="text-success">publik</span>';
                return '<span class="text-secondary">privasi</span>';
            }
            function makeButton(file) {
                const buttons = [_delete.render(function (e){
                    _transition.in();
                    _response.post({async:false, url:'{{url('archiveDelete')}}', data:{id:file.id}, file:new FormData()});
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
                    _popup.content(output);
                    _transition.out();
                })];
                if (file.archivefile.enable_public)
                    buttons.push(_btn.render({
                        size : 'sm',
                        operate : 'ubah',
                        type : 'success',
                        title : 'mengubah hak akses',
                        content : '<i class="ti-pencil font-weight-medium"></i>',
                        fun : function () {
                            _transition.in();
                            window.open('{{url('/archive/')}}'+file.token + folder.token, '_blank').focus();
                            _transition.out();
                        }
                    }));

                return _btn_group.make(buttons);
            }

            const id  = folder.id;
            const key = 'files-' + id;
            let content;
            if (folder.archives.length > 0) {
                const archives = folder.archives;
                content = _tables.render({
                    element  : key + '-table',
                    template : 'custom',
                    column   : [
                        {content : 'Nama'},
                        {content : 'Token'},
                        {content : 'Jenis File'},
                        {content : 'Akses'},
                        {content : 'Dibuat Pada'},
                        {content : 'Aksi'},
                    ],
                });
                for (let i = 0; i < archives.length; i++) {
                    const archive = archives[i];
                    _tables.insert({
                        element : key + '-table',
                        column  : [
                            {content : '<span class="font-weight-medium">' + archive.name + '</span>'},
                            {content : '<span class="font-weight-medium">' + archive.token + '</span>'},
                            {content : '<span class="font-weight-medium">' + archive.extension + '</span>'},
                            {content : '<span class="font-weight-medium">' + access_maker(archive.archivefile) + '</span>'},
                            {content : '<span class="font-weight-medium">' + _date.convert_created_at(archive.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '&nbsp;&nbsp;&nbsp;<small class="text-muted">oleh</small> ' + itsMe(archive.officer) + '</span>'},
                            {content : makeButton(archive)},
                        ],
                    });
                }
                _empty[key] = undefined;
            } else {
                content = document.createElement('div');
                content.setAttribute('class', 'text-center text-muted');
                content.innerHTML = '<div class="m-4 p-4">tidak ada berkas pada arsip ' + folder.name + '.</div>';
                _empty[key] = folder.name;
            }
            _files[key] = content;

            return key;
        }
        function itsMe(officer) {
            if(officer.id === {!! \App\Http\back\_Authorize::data()->officer->id; !!})
                return 'saya sendiri';
            return officer.user.name;
        }
        function buttons(folder, key) {
            if (folder.archives.length > 0)
                return _btn_group.make([
                    _btn.render({
                        size : 'sm',
                        operate : 'ubah',
                        type : 'warning',
                        title : 'ubah',
                        content : '<span class="text-white"><i class="ti-pencil font-weight-medium"></i></span>',
                        fun : function (e) {
                            window.focus_tar = e.target;
                            document.getElementById('form-edit-ip-0').value = folder.id + '';
                            document.getElementById('form-edit-ip-1').value = folder.name;
                            _card.show('edit-archive');
                            _card.focus('edit-archive');
                        }
                    }),
                    _delete.render(function (e) {
                        _transition.in();
                        let tr = e.target;
                        while (tr.nodeName !== 'TR') {tr = tr.parentNode;}
                        const par   = tr.parentNode;
                        const param = {async:false, url:'{{url('typeDelete')}}', data:{id:folder.id}, file:new FormData()};
                        _response.post(param);
                        let output;
                        const res = _response.response;
                        if (res._status) {
                            if (res.status === 'success') {
                                par.removeChild(tr);
                                _card.content('files-archive', document.createElement('div'));
                                _files[key] = undefined;
                                _empty[key] = undefined;
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
                        _popup.content(output);
                        _transition.out();
                    }),
                    _btn.render({
                        size : 'sm',
                        operate : 'unduh',
                        type : 'success',
                        title : 'unduh arsip',
                        content : '<span><i class="ti-download font-weight-medium"></i></span>',
                        fun : function () {
                            _transition.in();
                            window.open('{{url('/type/')}}' + folder.token, '_blank').focus();
                            _transition.out();
                        }
                    }),
                    _btn.render({
                        size : 'sm',
                        operate : 'bersihkan',
                        type : 'secondary',
                        title : 'bersihkan',
                        content : '<span class="text-white">bersihkan</span>',
                        fun : function (e) {
                            _transition.in();
                            let tr = e.target;
                            while (tr.nodeName !== 'TR') {tr = tr.parentNode;}
                            _response.post({async:false, url:'{{url('typeClear')}}', data:{id:folder.id}, file:new FormData()});
                            const res = _response.response;
                            let output;
                            if (res._status) {
                                if (res.status === 'success') {
                                    const td_amount   = tr.childNodes[2];
                                    const td_date     = tr.childNodes[2];
                                    const td_button   = tr.childNodes[4];
                                    const archivetype = res.archivetype;
                                    td_amount.innerHTML = '<span class="font-weight-medium">' + folder.archives.length + ' <small class="text-muted">arsip</small></span>';
                                    td_date.innerHTML   = '<span class="font-weight-medium">' + _date.convert_created_at(archivetype.updated_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '&nbsp;&nbsp;&nbsp;<small class="text-muted">oleh</small> ' + itsMe(archivetype.officer) + '</span>';
                                    td_button.innerHTML = '';
                                    makeFiles(archivetype);
                                    td_button.appendChild(buttons(archivetype, key));
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
                            _popup.content(output);
                            _transition.out();
                        }
                    }),
                ]);
            return _btn_group.make([
                _btn.render({
                    size : 'sm',
                    operate : 'ubah',
                    type : 'warning',
                    title : 'ubah',
                    content : '<span class="text-white"><i class="ti-pencil font-weight-medium"></i></span>',
                    fun : function (e) {
                        window.focus_tar = e.target;
                        document.getElementById('form-edit-ip-0').value = folder.id + '';
                        document.getElementById('form-edit-ip-1').value = folder.name;
                        _card.show('edit-archive');
                        _card.focus('edit-archive');
                    }
                }),
                _delete.render(function (e) {
                    _transition.in();
                    let tr = e.target;
                    while (tr.nodeName !== 'TR') {tr = tr.parentNode;}
                    const par   = tr.parentNode;
                    const param = {async:false, url:'{{url('typeDelete')}}', data:{id:folder.id}, file:new FormData()};
                    _response.post(param);
                    let output;
                    const res = _response.response;
                    if (res._status) {
                        if (res.status === 'success') {
                            par.removeChild(tr);
                            _card.content('files-archive', document.createElement('div'));
                            _files[key] = undefined;
                            _empty[key] = undefined;
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
                    _popup.content(output);
                    _transition.out();
                }),
            ]);
        }
        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'archive-folder',
            items : [
                {
                    title : 'Data Arsip',
                    label : 'ti-archive',
                    id : 'folder-archive',
                    content : concat('div', [
                        makeTypeInput(),
                        _tables.render({
                            element : 'archive-types',
                            template : 'custom',
                            column : [
                                {content : 'Jenis'},
                                {content : 'Kode'},
                                {content : 'Jumlah'},
                                {content : 'Terakhir Diubah'},
                                {content : 'Aksi'},
                            ]
                        })
                    ])
                },
                {
                    title : 'Ubah Info Arsip',
                    label : 'ti-pencil',
                    id    : 'edit-archive',
                    content : _formfield.render({
                        element : 'form-edit',
                        width : ['sm-12','md-9','lg-6','xl-5'],
                        fields : [
                            {name:'id', type:'hidden'},
                            {name:'nama', type: 'text', placeholder: 'nama arsip', value:'nama arsip', header: 'Nama Arsip'},
                            {},
                            {type:'submit', placeholder: 'ubah'},
                        ]
                    })[0]
                }
            ]
        });
        _card.render({
            element : 'archive-detail',
            items : [
                {
                    title : 'Isi Arsip',
                    label : 'ti-archive',
                    id : 'files-archive',
                    content : _tables.render({
                        element : 'archive-list',
                        template : 'custom',
                        column : []
                    })
                }
            ]
        });
        let node_iter = _folder._first;
        while (node_iter !== undefined) {
            const folder = node_iter;
            const key    = makeFiles(folder);
            const editor = itsMe(folder.officer);
            _tables.insert({
                element : 'archive-types',
                column  : [
                    {
                        content : '<span class="font-weight-medium">' + folder.name + '</span>',
                        click   : function (e) {
                            window.focus_key = key;
                            window.focus_tar = e.target;
                            document.getElementById('form-edit-ip-0').value = folder.id + '';
                            document.getElementById('form-edit-ip-1').value = folder.name;
                            _card.content('files-archive', _files[key]);
                            _card.show('edit-archive');
                        },
                        dblclick : function () {
                            _card.focus('edit-archive');
                        }
                    },
                    {content : '<span class="font-weight-medium">' + folder.code + '</span>'},
                    {content : '<span class="font-weight-medium">' + folder.archives.length + ' <small class="text-muted">arsip</small></span>'},
                    {content : '<span class="font-weight-medium">' + _date.convert_created_at(folder.updated_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '&nbsp;&nbsp;&nbsp;<small class="text-muted">oleh</small> ' + editor + '</span>'},
                    {
                        content: buttons(folder, key)
                    }
                ],
            });
            node_iter = node_iter._next;
        }
        _card.content('files-archive', _files['files-' + _folder._first.id]);
        collections.set({
            name   : 'form-edit',
            submit : 'form-edit-ip-3-submit',
            fields : [
                {el: 'form-edit-ip-0', name: 'id', hasVal:true},
                {el: 'form-edit-ip-1', name: 'name', hasVal:true, validator:'noEmpty'},
            ],
        });
        collections.set({
            name   : 'form-jenis-arsip',
            submit : 'btn-jenis-arsip',
            fields : [
                {el: 'form-jenis-arsip', name: 'name', validator:'noEmpty'},
            ],
        });
        document.getElementById('form-edit-ip-3-submit').addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-edit');
            _response.post({async:false, url:'{{url('typeEdit')}}', data:data[0], file:data[1]});
            const res = _response.response;
            if (res._status && res.status === 'success') {
                const archivetype = res.archivetype;
                let tr = window.focus_tar;
                while (tr.nodeName !== 'TR') {tr = tr.parentNode;}
                let td_name   = tr.firstChild;
                let td_size   = tr.children[2];
                let td_change = tr.children[3];
                let td_button = tr.children[4];
                td_name.innerHTML   = '<span class="font-weight-medium">' + archivetype.name + '</span>';
                td_size.innerHTML   = '<span class="font-weight-medium">' + archivetype.archives.length + ' <small class="text-muted">arsip</small></span>';
                td_change.innerHTML = '<span class="font-weight-medium">' + _date.convert_created_at(archivetype.updated_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '&nbsp;&nbsp;&nbsp;<small class="text-muted">oleh</small> ' + itsMe(archivetype.officer) + '</span>';
                td_button.innerHTML = '';
                td_button.appendChild(buttons(archivetype, key));
                makeFiles(archivetype);
                _card.focus('folder-archive');
                _card.hide('edit-archive');
                _card.content('files-archive', _files[key]);
            } else {
                let output;
                if (!res._status) {
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
                _popup.content(output);
            }
            _transition.out();
        });
        document.getElementById('btn-jenis-arsip').addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-jenis-arsip');
            _response.post({async:false, url:'{{url('typeInsert')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    document.getElementById('form-jenis-arsip').value = '';
                    const folder = res.archivetype;
                    const key    = makeFiles(folder);
                    const editor = itsMe(folder.officer);
                    _tables.insertFirst({
                        element : 'archive-types',
                        column  : [
                            {
                                content : '<span class="font-weight-medium">' + folder.name + '</span>',
                                click   : function (e) {
                                    window.focus_tar = e.target;
                                    window.focus_key = key;
                                    document.getElementById('form-edit-ip-0').value = folder.id + '';
                                    document.getElementById('form-edit-ip-1').value = folder.name;
                                    _card.content('files-archive', _files[key]);
                                    _card.show('edit-archive');
                                },
                                dblclick : function () {
                                    _card.focus('edit-archive');
                                }
                            },
                            {content : '<span class="font-weight-medium">' + folder.code + '</span>'},
                            {content : '<span class="font-weight-medium">' + folder.archives.length + ' <small class="text-muted">arsip</small></span>'},
                            {content : '<span class="font-weight-medium">' + _date.convert_created_at(folder.updated_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '&nbsp;&nbsp;&nbsp;<small class="text-muted">oleh</small> ' + editor + '</span>'},
                            {
                                content: buttons(folder, key)
                            }
                        ],
                    });
                    _card.content('files-archive', _files[key]);
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
            _popup.content(output);
            _transition.out();
        });
        _card.hide('edit-archive');
        _popup.init({element : 'popup-notification', align : 'center',});
        _transition.out();
    </script>
@endsection
