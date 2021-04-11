@extends('admin.template')

@section('title')
    Penduduk
@endsection

@section('page-breadcrumb')
    Data Penduduk
@endsection

@section('sub-breadcrumb')
    Halaman Info Data Penduduk {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-penduduk"></div>
    <div id="data-agama"></div>
    <div id="data-pendidikan"></div>
    <div id="data-pekerjaan"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
    <div id="popup-delete-record"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _citizens = _data.citizen, _religions = _data.religion, _educations = _data.education, _occupations = _data.occupation;
        _citizens.refresh(function () {
            _response.get('{{url('/citizens'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(district,hamlet,neighboor,citeducation,citeducation.education,citreligion,citreligion.religion,citoccupation,citoccupation.occupation)')}}',false);
            return _response.response;
        });
        _religions.refresh(function () {
            _response.get('{{url('/religions'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(citreligions)')}}',false);
            return _response.response;
        });
        _educations.refresh(function () {
            _response.get('{{url('/educations'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(citeducations)')}}',false);
            return _response.response;
        });
        _occupations.refresh(function () {
            _response.get('{{url('/occupations'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(citoccupations)')}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function inlineForm(id, placeholder, btn_text, fun) {
            const ctr   = document.createElement('form');
            const ct1   = document.createElement('div');
            const label = document.createElement('label');
            const input = document.createElement('input');
            const btn   = document.createElement('button');

            ctr.setAttribute('class', 'form-inline mb-2 mt-4');
            ct1.setAttribute('class', 'form-group mx-sm-3 mb-2');
            label.setAttribute('class', 'd-none');
            label.setAttribute('for', 'ip-' + id);
            input.setAttribute('type', 'text');
            input.setAttribute('class', 'form-control');
            input.setAttribute('id', 'ip-' + id);
            input.setAttribute('placeholder', placeholder);
            btn.innerHTML = btn_text;
            btn.setAttribute('id', 'submit-' + id);
            btn.setAttribute('type', 'submit');
            btn.setAttribute('class', 'btn btn-success mb-2');
            btn.addEventListener('click', function (e) {
                fun(e);
            });

            ctr.appendChild(ct1);
            ctr.appendChild(btn);
            ct1.appendChild(label);
            ct1.appendChild(input);

            return ctr;
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
        function setMarriage(citizen) {
            if (citizen.marriage)
                return 'sudah menikah';
            return 'belum menikah';
        }
        const empty_contents = {};
        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'data-penduduk',
            items : [
                {
                    title : 'Data Penduduk',
                    label : 'ti-user',
                    id : 'data-penduduk',
                    content : concat('div', [
                        _tables.render({
                            element : 'table-citizen',
                            template : 'custom',
                            column : [
                                {content : 'Profil'},
                                {content : 'Gender'},
                                {content : 'Umur/Status'},
                                {content : 'Tempat/Tgl. Lahir'},
                                {content : 'Gol. Darah'},
                                {content : 'Pendidikan'},
                                {content : 'Profesi'},
                                {content : 'Agama'},
                                {content : 'Dusun'},
                                {content : 'RT'},
                                {content : 'RW'},
                            ]
                        })
                    ]),
                }
            ]
        });
        _card.render({
            element : 'data-agama',
            items   : [
                {
                    title : 'Data Agama',
                    label : 'ti-key',
                    id    : 'religions-table',
                    content : concat('div', [
                        inlineForm('add-religion', 'nama agama', 'tambah', function () {
                            _transition.in();
                            const data = collections.collect('add-religion');
                            _response.post({async:false, url:'{{url('religionInsert')}}', data:data[0], file:data[1]});
                            const res = _response.response;
                            let output;
                            if (res._status) {
                                if (res.status === 'success') {
                                    const religion = res.religion;
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
                                    _tables.insertFirst({
                                        element : 'table-religion',
                                        column  : [
                                            {content : '<span class="font-weight-medium">' + religion.name + '</span>'},
                                            {content : '<span class="font-weight-medium">0</span> <small class="text-muted">jiwa</small>'},
                                            {content : '<span class="font-weight-medium">' + _date.convert_created_at(religion.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                                            {
                                                content : _delete.render(function (e) {
                                                    const event = e;
                                                    _popup.content({
                                                        id : 'popup-delete-record',
                                                        header : '<strong>hapus data</strong>',
                                                        content : '<p>apakah anda yakin ingin menghapus <code class="font-weight-bold">'+religion.name+'</code> dari data agama?</p>',
                                                        footer : _btn_group.make([
                                                            _btn.render({
                                                                operate : 'batal',
                                                                type : 'success',
                                                                title : 'batal',
                                                                content : 'batal',
                                                                fun : function () {
                                                                    _popup.close('popup-delete-record');
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
                                                                    _response.post({async:false, url:'{{url('religionDelete')}}', data:{id:religion.id}, file: new FormData()});
                                                                    if (_response.response._status) {
                                                                        let target = event.target;
                                                                        let parent = target.parentNode;
                                                                        while (parent.nodeName !== 'TR') {
                                                                            parent = parent.parentNode;
                                                                        }
                                                                        target = parent;
                                                                        parent = parent.parentNode;
                                                                        parent.removeChild(target);
                                                                        const citizens = document.getElementsByClassName('rel-' + education.id);
                                                                        for (let i = 0; i < citizens.length; i++) {
                                                                            citizens[i].innerHTML = empty_contents['religion_obj'].name;
                                                                        }
                                                                    }
                                                                    _transition.out();
                                                                }
                                                            }),
                                                        ]),
                                                    });
                                                })
                                            },
                                        ],
                                    });
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
                        }),
                        _tables.render({
                            element : 'table-religion',
                            template : 'custom',
                            column : [
                                {content: 'Agama'},
                                {content: 'Jumlah Penganut'},
                                {content: 'Ditambahkan pada'},
                                {content: '<span style="opacity: 0">Aksi</span>'},
                            ],
                        })
                    ])
                }
            ]
        });
        _card.render({
            element : 'data-pendidikan',
            items   : [
                {
                    title : 'Data Pendidikan',
                    label : 'ti-book',
                    id    : 'educations-table',
                    content : concat('div', [
                        inlineForm('add-education', 'nama tingkat pendidikan', 'tambah', function (e) {
                            _transition.in();
                            const data = collections.collect('add-education');
                            _response.post({async:false, url:'{{url('educationInsert')}}', data:data[0], file:data[1]});
                            const res = _response.response;
                            let output;
                            if (res._status) {
                                if (res.status === 'success') {
                                    const education = res.education;
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
                                    _tables.insertFirst({
                                        element : 'table-religion',
                                        column  : [
                                            {content : '<span class="font-weight-medium">' + education.name + '</span>'},
                                            {content : '<span class="font-weight-medium">0</span> <small class="text-muted">jiwa</small>'},
                                            {content : '<span class="font-weight-medium">' + _date.convert_created_at(education.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                                            {
                                                content : _delete.render(function (e) {
                                                    const event = e;
                                                    _popup.content({
                                                        id : 'popup-delete-record',
                                                        header : '<strong>hapus data</strong>',
                                                        content : '<p>apakah anda yakin ingin menghapus <code class="font-weight-bold">'+education.name+'</code> dari data pendidikan?</p>',
                                                        footer : _btn_group.make([
                                                            _btn.render({
                                                                operate : 'batal',
                                                                type : 'success',
                                                                title : 'batal',
                                                                content : 'batal',
                                                                fun : function () {
                                                                    _popup.close('popup-delete-record');
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
                                                                    _response.post({async:false, url:'{{url('educationDelete')}}', data:{id:education.id}, file: new FormData()});
                                                                    if (_response.response._status) {
                                                                        let target = event.target;
                                                                        let parent = target.parentNode;
                                                                        while (parent.nodeName !== 'TR') {
                                                                            parent = parent.parentNode;
                                                                        }
                                                                        target = parent;
                                                                        parent = parent.parentNode;
                                                                        parent.removeChild(target);
                                                                    }
                                                                    _transition.out();
                                                                }
                                                            }),
                                                        ]),
                                                    });
                                                })
                                            },
                                        ],
                                    });
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
                        }),
                        _tables.render({
                            element : 'table-education',
                            template : 'custom',
                            column : [
                                {content: 'Pendidikan'},
                                {content: 'Jumlah Orang'},
                                {content: 'Ditambahkan pada'},
                                {content: '<span style="opacity: 0">Aksi</span>'},
                            ],
                        })
                    ])
                }
            ]
        });
        _card.render({
            element : 'data-pekerjaan',
            items   : [
                {
                    title : 'Data Pekerjaan',
                    label : 'ti-briefcase',
                    id    : 'occupations-table',
                    content : concat('div', [
                        inlineForm('add-occupation', 'nama pekerjaan', 'tambah', function (e) {
                            _transition.in();
                            const data = collections.collect('add-occupation');
                            _response.post({async:false, url:'{{url('occupationInsert')}}', data:data[0], file:data[1]});
                            const res = _response.response;
                            let output;
                            if (res._status) {
                                if (res.status === 'success') {
                                    const occupation = res.occupation;
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
                                    _tables.insertFirst({
                                        element : 'table-religion',
                                        column  : [
                                            {content : '<span class="font-weight-medium">' + occupation.name + '</span>'},
                                            {content : '<span class="font-weight-medium">0</span> <small class="text-muted">jiwa</small>'},
                                            {content : '<span class="font-weight-medium">' + _date.convert_created_at(occupation.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                                            {
                                                content : _delete.render(function (e) {
                                                    const event = e;
                                                    _popup.content({
                                                        id : 'popup-delete-record',
                                                        header : '<strong>hapus data</strong>',
                                                        content : '<p>apakah anda yakin ingin menghapus <code class="font-weight-bold">'+occupation.name+'</code> dari data pekerjaan?</p>',
                                                        footer : _btn_group.make([
                                                            _btn.render({
                                                                operate : 'batal',
                                                                type : 'success',
                                                                title : 'batal',
                                                                content : 'batal',
                                                                fun : function () {
                                                                    _popup.close('popup-delete-record');
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
                                                                    _response.post({async:false, url:'{{url('occupationDelete')}}', data:{id:occupation.id}, file: new FormData()});
                                                                    if (_response.response._status) {
                                                                        let target = event.target;
                                                                        let parent = target.parentNode;
                                                                        while (parent.nodeName !== 'TR') {
                                                                            parent = parent.parentNode;
                                                                        }
                                                                        target = parent;
                                                                        parent = parent.parentNode;
                                                                        parent.removeChild(target);
                                                                    }
                                                                    _transition.out();
                                                                }
                                                            }),
                                                        ]),
                                                    });
                                                })
                                            },
                                        ],
                                    });
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
                        }),
                        _tables.render({
                            element : 'table-occupation',
                            template : 'custom',
                            column : [
                                {content: 'Pekerjaan'},
                                {content: 'Jumlah Orang'},
                                {content: 'Ditambahkan pada'},
                                {content: '<span style="opacity: 0">Aksi</span>'},
                            ],
                        })
                    ])
                }
            ]
        });
        let node_iter = _citizens._first;
        while (node_iter !== undefined) {
            const citizen = node_iter;
            const age      = _date.age(citizen.year_birth, citizen.month_birth, citizen.day_birth);
            const born     = _date.date(citizen.day_birth, citizen.month_birth, citizen.year_birth, ' ');
            const img      = setPhoto(citizen.pic);
            const marriage = setMarriage(citizen);
            _tables.insert({
                element : 'table-citizen',
                column  : [
                    {
                        content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+img+'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+citizen.name+'</h5><span class="text-muted font-14">'+citizen.identity+'</span></div></div>',
                    },
                    {content : '<span class="font-weight-medium">' + citizen.gender + '</span>'},
                    {content : '<span class="font-weight-medium">' + age + '</span> <small class="text-muted">tahun</small> / <span class="font-weight-medium">' + marriage + '</span>'},
                    {content : '<span class="font-weight-medium">' + citizen.place_birth + ' , ' + born + '</span>'},
                    {content : '<span class="font-weight-medium">' + citizen.blood + '</span>'},
                    {content : '<span class="font-weight-medium"><span class="edu-' + citizen.citeducation.education.id + '">' + citizen.citeducation.education.name + '</span></span>'},
                    {content : '<span class="font-weight-medium"><span class="occ-' + citizen.citoccupation.occupation.id + '">' + citizen.citoccupation.occupation.name + '</span></span>'},
                    {content : '<span class="font-weight-medium"><span class="rel-' + citizen.citreligion.religion.id + '">' + citizen.citreligion.religion.name + '</span></span>'},
                    {content : '<span class="font-weight-medium">' + citizen.district.name + '</span>'},
                    {content : '<span class="font-weight-medium">' + citizen.hamlet.name + '</span>'},
                    {content : '<span class="font-weight-medium">' + citizen.neighboor.name + '</span>'},

                ]
            });
            node_iter     = node_iter._next;
        }
        node_iter     = _religions._first;
        while (node_iter !== undefined) {
            const religion = node_iter;
            if (religion.name === 'Tidak Ada')
                empty_contents['religion_obj'] = religion;
            else
                _tables.insert({
                    element : 'table-religion',
                    column  : [
                        {content : '<span class="font-weight-medium">' + religion.name + '</span>'},
                        {content : '<span class="font-weight-medium">' + religion.citreligions.length + '</span> <small class="text-muted">jiwa</small>'},
                        {content : '<span class="font-weight-medium">' + _date.convert_created_at(religion.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                        {
                            content : _delete.render(function (e) {
                                const event = e;
                                _popup.content({
                                    id : 'popup-delete-record',
                                    header : '<strong>hapus data</strong>',
                                    content : '<p>apakah anda yakin ingin menghapus <code class="font-weight-bold">'+religion.name+'</code> dari data agama?</p>',
                                    footer : _btn_group.make([
                                        _btn.render({
                                            operate : 'batal',
                                            type : 'success',
                                            title : 'batal',
                                            content : 'batal',
                                            fun : function () {
                                                _popup.close('popup-delete-record');
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
                                                _response.post({async:false, url:'{{url('religionDelete')}}', data:{id:religion.id}, file: new FormData()});
                                                if (_response.response._status) {
                                                    let target = event.target;
                                                    let parent = target.parentNode;
                                                    while (parent.nodeName !== 'TR') {
                                                        parent = parent.parentNode;
                                                    }
                                                    target = parent;
                                                    parent = parent.parentNode;
                                                    parent.removeChild(target);
                                                }
                                                _transition.out();
                                            }
                                        }),
                                    ]),
                                });
                            })
                        },
                    ],
                });
            node_iter = node_iter._next;
        }
        _tables.insert({
            element : 'table-religion',
            column  : [
                {content : '<span class="font-weight-medium text-danger">' + empty_contents['religion_obj'].name + '</span>'},
                {content : '<span id="count-rel" class="font-weight-medium">' + empty_contents['religion_obj'].citreligions.length + '</span> <small class="text-muted">jiwa</small>'},
                {content : '<span class="font-weight-medium">' + _date.convert_created_at(empty_contents['religion_obj'].created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                {content : ''}
            ],
        });
        node_iter     = _educations._first;
        while (node_iter !== undefined) {
            const education = node_iter;
            if (education.name === 'Tidak Ada')
                empty_contents['education_obj'] = education;
            else
                _tables.insert({
                    element : 'table-education',
                    column  : [
                        {content : '<span class="font-weight-medium">' + education.name + '</span>'},
                        {content : '<span class="font-weight-medium">' + education.citeducations.length + '</span> <small class="text-muted">jiwa</small>'},
                        {content : '<span class="font-weight-medium">' + _date.convert_created_at(education.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                        {
                            content : _delete.render(function (e) {
                                const event = e;
                                _popup.content({
                                    id : 'popup-delete-record',
                                    header : '<strong>hapus data</strong>',
                                    content : '<p>apakah anda yakin ingin menghapus <code class="font-weight-bold">'+education.name+'</code> dari data pendidikan?</p>',
                                    footer : _btn_group.make([
                                        _btn.render({
                                            operate : 'batal',
                                            type : 'success',
                                            title : 'batal',
                                            content : 'batal',
                                            fun : function () {
                                                _popup.close('popup-delete-record');
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
                                                _response.post({async:false, url:'{{url('educationDelete')}}', data:{id:education.id}, file: new FormData()});
                                                if (_response.response._status) {
                                                    let target = event.target;
                                                    let parent = target.parentNode;
                                                    while (parent.nodeName !== 'TR') {
                                                        parent = parent.parentNode;
                                                    }
                                                    target = parent;
                                                    parent = parent.parentNode;
                                                    parent.removeChild(target);
                                                    const citizens = document.getElementsByClassName('edu-' + education.id);
                                                    for (let i = 0; i < citizens.length; i++) {
                                                        citizens[i].innerHTML = empty_contents['education_obj'].name;
                                                    }
                                                }
                                                _transition.out();
                                            }
                                        }),
                                    ]),
                                });
                            })
                        },
                    ],
                });
            node_iter = node_iter._next;
        }
        _tables.insert({
            element : 'table-education',
            column  : [
                {content : '<span class="font-weight-medium text-danger">' + empty_contents['education_obj'].name + '</span>'},
                {content : '<span id="count-edu" class="font-weight-medium">' + empty_contents['education_obj'].citeducations.length + '</span> <small class="text-muted">jiwa</small>'},
                {content : '<span class="font-weight-medium">' + _date.convert_created_at(empty_contents['education_obj'].created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                {
                    content : ''
                },
            ],
        });
        node_iter     = _occupations._first;
        while (node_iter !== undefined) {
            const occupation = node_iter;
            if (occupation.name === 'Tidak ada')
                empty_contents['occupation_obj'] = occupation;
            else
                _tables.insert({
                    element : 'table-occupation',
                    column  : [
                        {content : '<span class="font-weight-medium">' + occupation.name + '</span>'},
                        {content : '<span class="font-weight-medium">' + occupation.citoccupations.length + '</span> <small class="text-muted">jiwa</small>'},
                        {content : '<span class="font-weight-medium">' + _date.convert_created_at(occupation.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                        {
                            content : _delete.render(function (e) {
                                const event = e;
                                _popup.content({
                                    id : 'popup-delete-record',
                                    header : '<strong>hapus data</strong>',
                                    content : '<p>apakah anda yakin ingin menghapus <code class="font-weight-bold">'+occupation.name+'</code> dari data pekerjaan?</p>',
                                    footer : _btn_group.make([
                                        _btn.render({
                                            operate : 'batal',
                                            type : 'success',
                                            title : 'batal',
                                            content : 'batal',
                                            fun : function () {
                                                _popup.close('popup-delete-record');
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
                                                _response.post({async:false, url:'{{url('occupationDelete')}}', data:{id:occupation.id}, file: new FormData()});
                                                if (_response.response._status) {
                                                    let target = event.target;
                                                    let parent = target.parentNode;
                                                    while (parent.nodeName !== 'TR') {
                                                        parent = parent.parentNode;
                                                    }
                                                    target = parent;
                                                    parent = parent.parentNode;
                                                    parent.removeChild(target);
                                                    const citizens = document.getElementsByClassName('occ-' + education.id);
                                                    for (let i = 0; i < citizens.length; i++) {
                                                        citizens[i].innerHTML = empty_contents['occupation_obj'].name;
                                                    }
                                                }
                                                _transition.out();
                                            }
                                        }),
                                    ]),
                                });
                            })
                        },
                    ],
                });
            node_iter = node_iter._next;
        }
        _tables.insert({
            element : 'table-occupation',
            column  : [
                {content : '<span class="font-weight-medium text-danger">' + empty_contents['occupation_obj'].name + '</span>'},
                {content : '<span id="count-occ" class="font-weight-medium">' + empty_contents['occupation_obj'].citoccupations.length + '</span> <small class="text-muted">jiwa</small>'},
                {content : '<span class="font-weight-medium">' + _date.convert_created_at(empty_contents['occupation_obj'].created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                {content : ''},
            ],
        });
        collections.set({
            name   : 'add-religion',
            submit : 'submit-add-religion',
            fields : [
                {el: 'ip-add-religion', name: 'name', validator: 'name'},
            ],
        });
        collections.set({
            name   : 'add-education',
            submit : 'submit-add-education',
            fields : [
                {el: 'ip-add-education', name: 'name', validator: 'name'},
            ],
        });
        collections.set({
            name   : 'add-occupation',
            submit : 'submit-add-occupation',
            fields : [
                {el: 'ip-add-occupation', name: 'name', validator: 'name'},
            ],
        });
        _popup.init({element : 'popup-notification', align : 'center',});
        _popup.init({element : 'popup-delete-record', align : 'center',});
    </script>
@endsection
