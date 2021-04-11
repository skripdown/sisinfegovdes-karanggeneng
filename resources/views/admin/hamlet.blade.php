@extends('admin.template')

@section('title')
    Rukun Tetangga
@endsection

@section('page-breadcrumb')
    Kelola Data RT
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Data RT {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-rt"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _hamlets = _data.hamlet, _district = _data.district;
        _hamlets.refresh(function () {
            _response.get('{{url('/hamlets'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(district,neighboors,neighboors.families,neighboors.citizens,families,citizens)')}}',false);
            return _response.response;
        });
        _district.refresh(function () {
            _response.get('{{url('/districts'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function tableContent(hamlet, empty=false) {
            const neighbors = hamlet.neighboors;
            const table = _tables.render({
                element : 'table-' + hamlet.name + '-' + hamlet.id,
                template : 'custom',
                column : [
                    {content: 'RW'},
                    {content: 'Jumlah Keluarga'},
                    {content: 'Jumlah Penduduk'},
                ],
            });

            if (empty) {
                _tables.insert({
                    element : 'table-' + hamlet.name + '-' + hamlet.id,
                    column : [
                        {content : '<span class="font-weight-medium">-</span>'},
                        {content : '<span class="font-weight-medium">0</span> <small class="text-muted">keluarga<small>'},
                        {content : '<span class="font-weight-medium">0</span> <small class="text-muted">orang<small>'}
                    ],
                });
            } else {
                for (let i = 0; i < neighbors.length; i++) {
                    const neighbor = neighbors[i];
                    _tables.insert({
                        element : 'table-' + hamlet.name + '-' + hamlet.id,
                        column : [
                            {content : '<span class="font-weight-medium">' + neighbor.name + '</span>'},
                            {content : '<span class="font-weight-medium">' + neighbor.families.length + '</span> <small class="text-muted">keluarga<small>'},
                            {content : '<span class="font-weight-medium">' + neighbor.citizens.length + '</span> <small class="text-muted">orang<small>'}
                        ],
                    });
                }
            }

            return table;
        }
        function makeSelectDistrict(select, district) {
            const input     = document.getElementById(select);
            input.innerHTML = '';
            let node_iter = district._first;
            while (node_iter !== undefined) {
                const node = node_iter;
                const opt_ = document.createElement('option');
                opt_.setAttribute('value',node.id);
                opt_.innerHTML = node.name;
                input.appendChild(opt_);
                node_iter  = node_iter._next;
            }
            input.firstChild.selected = true;
        }
        function setSelect(select, id) {
            const input = document.getElementById(select);
            const children = input.children;
            for (let i = 0; i < children.length; i++) {
                if (children[i].getAttribute('value') === id+'') {
                    children[i].selected = true;
                }
            }
        }

        const concat = _ui_factory.__general.compact_els;
        const hamlet_contents = {};
        _card.render({
            element : 'data-rt',
            items : [
                {
                    title : 'Data Rukun Tetangga',
                    label : 'ti-layout-grid3',
                    id : 'hamlets-data',
                    content : _tables.render({
                        element : 'table-hamlet',
                        template : 'custom',
                        column : [
                            {content : 'RT'},
                            {content : 'Dusun'},
                            {content : 'Jumlah RW'},
                            {content : 'Jumlah Keluarga'},
                            {content : 'Jumlah Penduduk'},
                        ]
                    })
                },
                {
                    title : 'Tambah RT',
                    label : 'ti-plus',
                    id : 'hamlets-add',
                    content: _formfield.render({
                        element : 'form-add',
                        width : ['sm-12','md-9','lg-6','xl-5'],
                        fields : [
                            {name:'dusun',type : 'select', values:[{value:'1',label:'ui'},{value:'2',label:'ua'},], header: 'Dusun'},
                            {name:'nomor', type: 'text', placeholder: 'nomor', header: 'Nomor RT'},
                            {},
                            {type:'submit', placeholder: 'tambah'},
                        ]
                    })[0]
                },
                {
                    title : 'Ubah RT',
                    label : 'ti-pencil-alt',
                    id : 'hamlets-edit',
                    content: concat('div',[
                        _formfield.render({
                            element : 'form-edit',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'id', type: 'hidden'},
                                {name:'dusun',type : 'select', values:[{value:'1',label:'ui'},{value:'2',label:'ua'},]},
                                {name:'nomor', type: 'text', placeholder: 'nomor', value:'nomor rt', header: 'Nomor Rukun Tetangga'},
                                {type:'submit', placeholder: 'ubah'},
                            ]
                        })[0],
                        _formfield.render({
                            element : 'form-delete',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'id',type : 'hidden',value:''},
                                {name:'nomor',type : 'hidden',value:'undefined'},
                                {name:'verify',type : 'text',placeholder:'konfirmasi nomor RT',header:'klik tombol <span class="bg-danger text-white pl-2 pr-2 rounded">hapus</span> untuk menghapus data RT.</br>menghapus data RT akan menghilangkan juga data <code>RW</code> dan data <code>kependudukan</code> lain di dalamnya.'},
                                {type:'delete'},
                            ]
                        })[0]
                    ])
                },
                {
                    title : 'Detail RT',
                    label : 'ti-info',
                    id : 'hamlets-detail',
                    content : _tables.render({
                        element : 'detail-rt',
                        template : 'custom',
                        column : [
                            {content: 'RW'},
                            {content: 'Jumlah Keluarga'},
                            {content: 'Jumlah Penduduk'},
                        ],
                    })
                }
            ]
        });
        makeSelectDistrict('form-add-ip-0', _district);
        makeSelectDistrict('form-edit-ip-1', _district);
        let hamlet_node = _hamlets._first;
        while (hamlet_node !== undefined) {
            const hamlet = hamlet_node;
            hamlet_contents[hamlet.name + '-' + hamlet.id] = tableContent(hamlet);
            _tables.insert({
                element : 'table-hamlet',
                column  : [
                    {
                        content :'<span class="font-weight-medium">' + hamlet.name + '</span>',
                        click   : function (e) {
                            window.touch = e.target;
                            document.getElementById('form-edit-ip-0').value   = hamlet.id;
                            document.getElementById('form-edit-ip-2').value   = hamlet.name;
                            setSelect('form-edit-ip-1', hamlet.district.id);
                            document.getElementById('form-delete-ip-0').value = hamlet.id;
                            document.getElementById('form-delete-ip-1').value = hamlet.name;
                            _card.content('hamlets-detail', hamlet_contents[hamlet.name + '-' + hamlet.id]);
                            _card.show('hamlets-edit');
                            _card.show('hamlets-detail');
                        },
                        dblclick : function (e) {
                            _card.show('hamlets-edit');
                            _card.show('hamlets-detail');
                            _card.focus('hamlets-detail');
                        }
                    },
                    {content : '<span class="font-weight-medium">' + hamlet.district.name + '</span>'},
                    {content : '<span class="font-weight-medium">' + hamlet.neighboors.length + '</span> <small>RW</small>'},
                    {content : '<span class="font-weight-medium">' + hamlet.families.length + '</span> <small>keluarga</small>'},
                    {content : '<span class="font-weight-medium">' + hamlet.citizens.length + '</span> <small>orang</small>'},
                ],
            });
            hamlet_node  = hamlet_node._next;
        }
        _valuematch.for({
            source : 'form-delete-ip-2',
            target : 'form-delete-ip-1',
            fun    : function () {
                const button = document.getElementById('form-delete-ip-3-dlt');
                let classes_ = button.getAttribute('class');
                classes_     = classes_.replace('disabled', '');
                button.setAttribute('class', classes_);
            },
        });
        collections.set({
            name   : 'form-add-hamlet',
            submit : 'form-add-ip-3-submit',
            fields : [
                {el: 'form-add-ip-0', name: 'district_id'},
                {el: 'form-add-ip-1', name: 'name', validator: 'number'},
            ],
        });
        collections.set({
            name   : 'form-edit-hamlet',
            submit : 'form-edit-ip-3-submit',
            fields : [
                {el: 'form-edit-ip-0', name: 'id', hasVal:true},
                {el: 'form-edit-ip-1', name: 'district_id'},
                {el: 'form-edit-ip-2', name: 'name', hasVal: true},
            ],
        });
        collections.set({
            name   : 'form-delete-hamlet',
            submit : 'form-delete-ip-3-dlt',
            fields : [
                {el: 'form-delete-ip-0', name: 'id', hasVal:true},
                {el: 'form-delete-ip-1', name:'name', hasVal:true},
            ],
        });
        const add_submit_btn = document.getElementById('form-add-ip-3-submit');
        const edt_submit_btn = document.getElementById('form-edit-ip-3-submit');
        const del_submit_btn = document.getElementById('form-delete-ip-3-dlt');
        add_submit_btn.addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-add-hamlet');
            _response.post({async:false, url:'{{url('hamletInsert')}}', data:data[0], file:data[1]});
            let output;
            if (_response.response._status) {
                if (_response.response.status === 'success') {
                    const res = _response.response;
                    hamlet_contents[res.hamlet.name + '-' + res.hamlet.id] = tableContent(res.hamlet,true);
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
                    _tables.insert({
                        element : 'table-hamlet',
                        column  : [
                            {
                                content : '<span class="font-weight-medium">' + res.hamlet.name + '</span>',
                                click   : function (e) {
                                    window.touch = e.target;
                                    document.getElementById('form-edit-ip-0').value   = res.hamlet.id;
                                    document.getElementById('form-edit-ip-2').value   = res.hamlet.name;
                                    setSelect('form-edit-ip-1', res.district.id);
                                    document.getElementById('form-delete-ip-0').value = res.hamlet.id;
                                    document.getElementById('form-delete-ip-1').value = res.hamlet.name;
                                    _card.content('hamlets-detail', hamlet_contents[res.hamlet.name + '-' + res.hamlet.id]);
                                    _card.show('hamlets-edit');
                                    _card.show('hamlets-detail');
                                },
                                dblclick : function (e) {
                                    _card.show('hamlets-edit');
                                    _card.show('hamlets-detail');
                                    _card.focus('hamlets-detail');
                                }
                            },
                            {content : '<span class="font-weight-medium">' + res.district.name + '</span>'},
                            {content : '<span class="font-weight-medium">0</span> <small>RW</small>'},
                            {content : '<span class="font-weight-medium">0</span> <small>keluarga</small>'},
                            {content : '<span class="font-weight-medium">0</span> <small>orang</small>'},

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
            _card.focus('hamlets-data');
            _card.hide('hamlets-edit');
            _transition.out();
            _popup.content(output);
        });
        edt_submit_btn.addEventListener('click', function (e) {
            _transition.in();
            const data = collections.collect('form-edit-hamlet');
            _response.post({async:false, url:'{{url('hamletEdit')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    let touch_row = window.touch;
                    while (touch_row.nodeName !== 'TR') {
                        touch_row = touch_row.parentNode;
                    }
                    touch_row.firstChild.firstChild.innerHTML = res.hamlet.name;
                    touch_row.children[1].firstChild.innerHTML = res.district.name;
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
            _card.focus('hamlets-data');
            _card.hide('hamlets-edit');
            _card.hide('hamlets-detail');
            _popup.content(output);
        });
        del_submit_btn.addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-delete-hamlet');
            _response.post({async:false, url:'{{url('hamletDelete')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    let touch_row = window.touch;
                    let parent_row;
                    while (touch_row.nodeName !== 'TR') {
                        touch_row = touch_row.parentNode;
                    }
                    parent_row = touch_row.parentNode;
                    parent_row.removeChild(touch_row);
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
            _card.focus('hamlets-data');
            _card.hide('hamlets-edit');
            _card.hide('hamlets-detail');
            _popup.content(output);
        });
        _card.hide('hamlets-edit');
        _card.hide('hamlets-detail');
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
