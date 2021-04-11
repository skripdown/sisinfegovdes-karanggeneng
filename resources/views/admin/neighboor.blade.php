@extends('admin.template')

@section('title')
    Rukun Warga
@endsection

@section('page-breadcrumb')
    Kelola Data RW
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Data RW {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-rw"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script src="{{asset(env('LIB_PATH').'core/skripdown/_district_input.js')}}"></script>
    <script>
        @include('root.token')
        const _neighbors = _data.neighboor, _districts = _data.district;
        _neighbors.refresh(function () {
            _response.get('{{'/neighboors'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(district,hamlet,families,families.citizens,citizens)'}}',false);
            return _response.response;
        });
        _districts.refresh(function () {
            _response.get('{{'/districts'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(hamlets)'}}', false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function tableContent(neighbor, empty=false) {
            const families = neighbor.families;
            const table    = _tables.render({
                element : 'table-' + neighbor.name + '-' + neighbor.id,
                template : 'custom',
                column : [
                    {content: 'Nomor Kartu Keluarga'},
                    {content: 'Jumlah Anggota'},
                    {content: 'Laki-laki'},
                    {content: 'Perempuan'},
                ],
            });

            if (empty) {
                _tables.insert({
                    element : 'table-' + neighbor.name + '-' + neighbor.id,
                    column : [
                        {content : '<span class="font-weight-medium">-</span>'},
                        {content : '<span class="font-weight-medium">0</span> <small class="text-muted">Orang<small>'},
                        {content : '<span class="font-weight-medium">0</span> <small class="text-muted">orang<small>'},
                        {content : '<span class="font-weight-medium">0</span> <small class="text-muted">orang<small>'}
                    ],
                });
            } else {
                for (let i = 0; i < families.length; i++) {
                    const family     = families[i];
                    const citizens   = family.citizens;
                    let count_male   = 0;
                    let count_female = 0;
                    for (let j = 0; j < citizens.length; j++) {
                        if (citizens[j].gender === 'laki-laki')
                            count_male++;
                        else
                            count_female++;
                    }
                    _tables.insert({
                        element : 'table-' + neighbor.name + '-' + neighbor.id,
                        column : [
                            {content : '<span class="font-weight-medium">' + family.number + '</span>'},
                            {content : '<span class="font-weight-medium">' + neighbor.citizens.length + '</span> <small class="text-muted">orang<small>'},
                            {content : '<span class="font-weight-medium">' + count_male + '</span> <small class="text-muted">orang<small>'},
                            {content : '<span class="font-weight-medium">' + count_female + '</span> <small class="text-muted">orang<small>'},
                        ],
                    });
                }
            }

            return table;
        }
        const concat = _ui_factory.__general.compact_els
        const neighbor_contents = {};
        _card.render({
            element : 'data-rw',
            items : [
                {
                    title : 'Data Rukun Warga',
                    label : 'ti-layout-grid3',
                    id : 'data-rukun-warga',
                    content : _tables.render({
                        element : 'table-neighbor',
                        template : 'custom',
                        column : [
                            {content : 'RW'},
                            {content : 'Dusun'},
                            {content : 'RT'},
                            {content : 'Jumlah Keluarga'},
                            {content : 'Jumlah Anggota'},
                        ]
                    })
                },
                {
                    title : 'Tambah RW',
                    label : 'ti-plus',
                    id : 'neighbors-add',
                    content: _formfield.render({
                        element : 'form-add',
                        width : ['sm-12','md-9','lg-6','xl-5'],
                        fields : [
                            {name:'district',type : 'select', values:[], header:'Dusun'},
                            {name:'hamlet',type : 'select', values:[], header:'RT'},
                            {name:'nomor', type: 'text', placeholder: 'nomor', header: 'Nomor RW'},
                            {},
                            {type:'submit', placeholder: 'tambah'},
                        ]
                    })[0]
                },
                {
                    title : 'Detail RW',
                    label : 'ti-pencil-alt',
                    id : 'neighbors-edit',
                    content: concat('div',[
                        _formfield.render({
                            element : 'form-edit',
                            width : ['sm-12','md-10','lg-8','xl-5'],
                            fields : [
                                {name:'id', type: 'hidden'},
                                {name:'district',type : 'select', values:[], header: 'Dusun'},
                                {name:'hamlet',type : 'select', values:[], header: 'RT'},
                                {name:'nomor', type: 'text', placeholder: 'nomor', value:'nomor rw', header: 'Nomor Rukun Warga'},
                                {},
                                {type:'submit', placeholder: 'ubah'},
                            ]
                        })[0],
                        _formfield.render({
                            element : 'form-delete',
                            width : ['sm-12','md-10','lg-8','xl-5'],
                            fields : [
                                {name:'id',type : 'hidden',value:''},
                                {name:'nomor',type : 'hidden',value:'undefined'},
                                {name:'verify',type : 'text',placeholder:'konfirmasi nomor RW',header:'klik tombol <span class="bg-danger text-white pl-2 pr-2 rounded">hapus</span> untuk menghapus data RW.</br>menghapus data RW akan menghilangkan juga data <code>kependudukan</code> lain di dalamnya.'},
                                {type:'delete'},
                            ]
                        })[0]
                    ])
                },
                {
                    title : 'Detail RW',
                    label : 'ti-info',
                    id : 'neighbors-detail',
                    content : _tables.render({
                        element : 'detail-rt',
                        template : 'custom',
                        column : [
                            {content: 'Jumlah Keluarga'},
                            {content: 'Jumlah Penduduk'},
                            {content: 'Jumlah Laki-laki'},
                            {content: 'Jumlah Perempuan'},
                        ],
                    })
                }
            ]
        });
        _district_input.make({
            key          : 'select-add',
            districts    : _districts,
            sel_district : 'form-add-ip-0',
            sel_hamlet   : 'form-add-ip-1',
            sel_neighbor : undefined,
        });
        _district_input.make({
            key          : 'select-edit',
            districts    : _districts,
            sel_district : 'form-edit-ip-1',
            sel_hamlet   : 'form-edit-ip-2',
            sel_neighbor : undefined,
        });
        let node_iter = _neighbors._first;
        while (node_iter !== undefined) {
            const neighbor = node_iter;
            neighbor_contents[neighbor.name + '-' + neighbor.id] = tableContent(neighbor);
            _tables.insert({
                element : 'table-neighbor',
                column  : [
                    {
                        content  : '<span class="font-weight-medium">' + neighbor.name + '</span>',
                        click    : function (e) {
                            window.touch = e.target;
                            document.getElementById('form-edit-ip-0').value   = neighbor.id;
                            document.getElementById('form-edit-ip-3').value   = neighbor.name;
                            _district_input.set(neighbor.district.id, neighbor.hamlet.id, undefined, 'select-edit');
                            document.getElementById('form-delete-ip-0').value = neighbor.id;
                            document.getElementById('form-delete-ip-1').value = neighbor.name;
                            _card.content('neighbors-detail',neighbor_contents[neighbor.name + '-' + neighbor.id]);
                            _card.show('neighbors-edit');
                            _card.show('neighbors-detail');
                        },
                        dblclick : function () {
                            _card.focus('neighbors-detail');
                        }
                    },
                    {content : '<span class="font-weight-medium">' + neighbor.district.name + '</span>'},
                    {content : '<span class="font-weight-medium">' + neighbor.hamlet.name + '</span>'},
                    {content : '<span class="font-weight-medium">' + neighbor.families.length + '</span> <small>keluarga</small>'},
                    {content : '<span class="font-weight-medium">' + neighbor.citizens.length + '</span> <small>orang</small>'},
                ],
            });
            node_iter      = node_iter._next;
        }
        _valuematch.for({
            source : 'form-delete-ip-2',
            target : 'form-delete-ip-1',
            fun    : function () {
                const button = document.getElementById('form-delete-ip-3-dlt');
                let classes_ = button.getAttribute('class');
                classes_     = classes_.replace('disabled', '');
                button.setAttribute('class', classes_);
            }
        });
        collections.set({
            name   : 'form-add-neighbor',
            submit : 'form-add-ip-4-submit',
            fields : [
                {el: 'form-add-ip-0', name: 'district_id'},
                {el: 'form-add-ip-1', name: 'hamlet_id'},
                {el: 'form-add-ip-2', name: 'name', validator: 'number'},
            ],
        });
        collections.set({
            name   : 'form-edit-neighbor',
            submit : 'form-edit-ip-5-submit',
            fields : [
                {el: 'form-edit-ip-0', name: 'id', hasVal: true},
                {el: 'form-edit-ip-1', name: 'district_id'},
                {el: 'form-edit-ip-2', name: 'hamlet_id',},
                {el: 'form-edit-ip-3', name: 'name', validator: 'number', hasVal: true},
            ],
        });
        collections.set({
            name   : 'form-delete-neighbor',
            submit : 'form-delete-ip-3-dlt',
            fields : [
                {el: 'form-delete-ip-0', name: 'id', hasVal:true},
                {el: 'form-delete-ip-1', name:'name', hasVal:true},
            ],
        });
        const add_submit_btn = document.getElementById('form-add-ip-4-submit');
        const edt_submit_btn = document.getElementById('form-edit-ip-5-submit');
        const del_submit_btn = document.getElementById('form-delete-ip-3-dlt');
        add_submit_btn.addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-add-neighbor');
            _response.post({async:false, url:'{{url('insertNeighbor')}}', data:data[0], file:data[1]});
            let output;
            const res = _response.response;
            if (res._status) {
                if (res.status === 'success') {
                    neighbor_contents[res.neighbor.name + '-' + res.neighbor.id] = tableContent(res.neighbor, true);
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
                        element : 'table-neighbor',
                        column  : [
                            {
                                content : '<span class="font-weight-medium">' + res.neighbor.name + '</span>',
                                click   : function (e) {
                                    window.touch = e.target;
                                    document.getElementById('form-edit-ip-0').value   = res.neighbor.id;
                                    document.getElementById('form-edit-ip-3').value   = res.neighbor.name;
                                    _district_input.set(res.neighbor.district.id, res.neighbor.hamlet.id, undefined, 'select-edit');
                                    document.getElementById('form-delete-ip-0').value = res.neighbor.id;
                                    document.getElementById('form-delete-ip-1').value = res.neighbor.name;
                                    _card.content('neighbors-detail',neighbor_contents[res.neighbor.name + '-' + res.neighbor.id]);
                                    _card.show('neighbors-edit');
                                    _card.show('neighbors-detail');
                                },
                                dblclick : function () {
                                    _card.focus('neighbors-detail');
                                }
                            },
                            {content : '<span class="font-weight-medium">' + res.district.name + '</span>'},
                            {content : '<span class="font-weight-medium">' + res.hamlet.name + '</span>'},
                            {content : '<span class="font-weight-medium">0</span> <small>keluarga</small>'},
                            {content : '<span class="font-weight-medium">0</span> <small>orang</small>'},
                        ]
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
            _card.focus('neighbors-data');
            _card.hide('neighbors-edit');
            _transition.out();
            _popup.content(output);
        });
        edt_submit_btn.addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-edit-neighbor');
            _response.post({async: false, url:'{{url('neighborEdit')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    let touch_row = window.touch;
                    while (touch_row.nodeName !== 'TR') {
                        touch_row = touch_row.parentNode;
                    }
                    touch_row.firstChild.firstChild.innerHTML = res.neighbor.name;
                    touch_row.children[1].firstChild.innerHTML = res.district.name;
                    touch_row.children[2].firstChild.innerHTML = res.hamlet.name;
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
            _card.focus('neighbors-data');
            _card.hide('neighbors-edit');
            _card.hide('neighbors-detail');
            _popup.content(output);
        });
        del_submit_btn.addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-delete-hamlet');
            _response.post({async:false, url:'{{url('neighborDelete')}}', data:data[0], file:data[1]});
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
            _card.focus('neighbors-data');
            _card.hide('neighbors-edit');
            _card.hide('neighbors-detail');
            _popup.content(output);
        });
        _card.hide('neighbors-edit');
        _card.hide('neighbors-detail');
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
