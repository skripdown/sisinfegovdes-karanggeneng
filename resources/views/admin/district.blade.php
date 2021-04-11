@extends('admin.template')

@section('title')
    Dusun
@endsection

@section('page-breadcrumb')
    Kelola Dusun
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Data Dusun {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-dusun"></div>
    <div id="data-stats"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _districts = _data.district,
            _educations  = _data.education,
            _religions   = _data.religion,
            _occupations = _data.occupation;
        _districts.refresh(function () {
            _response.get('{{url('/districts'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(hamlets,neighboors,families,citizens,hamlets.citizens.citeducation.education,hamlets.citizens.citreligion.religion,hamlets.citizens.citoccupation.occupation)')}}',false);
            return _response.response;
        });
        _educations.refresh(function () {_response.get('{{url('/educations'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});
        _religions.refresh(function () {_response.get('{{url('/religions'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});
        _occupations.refresh(function () {_response.get('{{url('/occupations'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});
    </script>
@endsection

@section('script-body')
    <script>
        function districtContent(district, table_head, chart_label, pointer, label, empty=false) {
            const content = {};
            const hamlets = district.hamlets;
            const table_temp = {};
            const chart_temp = {};
            const chart_val  = [];

            content['table'] = _tables.render({
                element : 'table-' + label + '-' + district.name + '-' + district.id,
                template : 'custom',
                column : table_head,
            });
            content['chart'] = _piechart.init({
                id : 'chart-' + label + '-' + district.name + '-' + district.id,
                height : 125,
                title : 'Perbandingan Jumlah Data ' + label,
                labels : chart_label,
            });

            if (empty) {
                const t_body = [{content:'<span class="text-muted">-</span>'}];
                const c_val_ = [];
                for (let i = 0; i < chart_label.length; i++) {
                    c_val_.push(0);
                    t_body.push({content: '<span class="text-muted">-</span>'});
                }
                _tables.insert({
                    element : 'table-' + label + '-' + district.name + '-' + district.id,
                    column  : t_body,
                });
                _piechart.render({
                    id : 'chart-' + label + '-' + district.name + '-' + district.id,
                    data : c_val_,
                });
            }
            else {
                for (let i = 0; i < table_head.length; i++) {
                    table_temp[table_head[i].content] = 0;
                    chart_temp[table_head[i].content] = 0;
                }
                for (let i = 0; i < hamlets.length; i++) {
                    const temp     = {...table_temp};
                    const hamlet   = hamlets[i];
                    const citizens = hamlet.citizens;
                    const t_body   = [{content:hamlet.name}];
                    for (let j = 0; j < citizens.length; j++) {
                        const citizen = citizens[j];
                        const target  = citizen['cit' + pointer][pointer].name;
                        temp[target]  = temp[target] + 1;
                        chart_temp[target] = chart_temp[target] + 1;
                    }
                    for (let j = 1; j < table_head.length; j++) {
                        let col = temp[table_head[j].content] + '';
                        if (col === '0')
                            col = '<span class="text-muted">' + col + '</span>';
                        t_body.push({content: col});
                    }
                    _tables.insert({
                        element : 'table-' + label + '-' + district.name + '-' + district.id,
                        column  : t_body,
                    });
                }
                for (let i = 1; i < table_head.length; i++) {
                    chart_val.push(chart_temp[table_head[i].content]);
                }
                _piechart.render({
                    id : 'chart-' + label + '-' + district.name + '-' + district.id,
                    data : chart_val,
                });
            }
            return concat('div', [content['chart'], content['table']], 'h');
        }

        const head_educations   = _tables.make_header({arr:[{content:'RT'}], src: _educations});
        const head_religions    = _tables.make_header({arr:[{content:'RT'}], src: _religions});
        const head_occupations  = _tables.make_header({arr:[{content:'RT'}], src: _occupations});
        const chart_educations  = _educations.toArray('name');
        const chart_religions   = _religions.toArray('name');
        const chart_occupations = _occupations.toArray('name');

        const concat = _ui_factory.__general.compact_els;
        const stat_contents  = {};
        window.touch = undefined;

        _card.render({
            element : 'data-dusun',
            items : [
                {
                    title : 'Data Dusun',
                    label : 'ti-layout-grid3',
                    id : 'districts-data',
                    content : _tables.render({
                        element : 'table-district',
                        template : 'custom',
                        column : [
                            {content : 'Nama'},
                            {content : 'Luas'},
                            {content : 'Jumlah RT'},
                            {content : 'Jumlah RW'},
                            {content : 'Jumlah Keluarga'},
                            {content : 'Jumlah Penduduk'},
                        ]
                    })
                },
                {
                    title : 'Tambah Dusun',
                    label : 'ti-plus',
                    id : 'districts-add',
                    content: _formfield.render({
                        element : 'form-add',
                        width : ['sm-12','md-9','lg-6','xl-5'],
                        fields : [
                            {name:'nama', type: 'text', placeholder: 'nama', header: 'Nama Dusun'},
                            {name:'luas', type: 'text', placeholder: 'luas', header: 'Luas Dusun m<sup>2</sup>'},
                            {},
                            {type:'submit', placeholder: 'tambah'},
                        ]
                    })[0]
                },
                {
                    title : 'Detail Dusun',
                    label : 'ti-pencil-alt',
                    id : 'districts-edit',
                    content: concat('div',[
                        _formfield.render({
                            element : 'form-edit',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'id',type : 'hidden',value:''},
                                {name:'nama', type: 'text', placeholder: 'nama', value:'nama dusun', header: 'Nama Dusun'},
                                {name:'luas', type: 'text', placeholder: 'luas', value:'luas dusun', header: 'Luas Dusun m<sup>2</sup>'},
                                {},
                                {type:'submit', placeholder: 'ubah'},
                            ]
                        })[0],
                        _formfield.render({
                            element : 'form-delete',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'id',type : 'hidden',value:''},
                                {name:'name',type : 'hidden',value:'undefined'},
                                {name:'verify',type : 'text',placeholder:'konfirmasi nama dusun',header:'klik tombol <span class="bg-danger text-white pl-2 pr-2 rounded">hapus</span> untuk menghapus data dusun.</br>menghapus data dusun akan menghilangkan juga data <code>RW</code>, <code>RT</code> dan data <code>kependudukan</code> lain di dalamnya.'},
                                {type:'delete'},
                            ]
                        })[0]
                    ])
                }
            ]
        });
        _card.render({
            element : 'data-stats',
            items : [
                {
                    title: 'Statistik Data Pendidikan',
                    label : 'ti-book',
                    id : 'stats-pendidikan',
                    content : concat('div',[
                        _piechart.init({
                            id : 'chart-pendidikan',
                            height : 100,
                            title : 'Perbandingan Jumlah Data Pendidikan',
                            labels : chart_educations,
                        }),
                        _tables.render({
                            element : 'table-pendidikan',
                            template : 'custom',
                            column : head_educations,
                        })
                    ], 'h')
                },
                {
                    title: 'Statistik Data Agama',
                    label : 'ti-key',
                    id : 'stats-agama',
                    content : concat('div',[
                        _piechart.init({
                            id : 'chart-agama',
                            height : 100,
                            title : 'Perbandingan Jumlah Data Agama',
                            labels : chart_religions,
                        }),
                        _tables.render({
                            element : 'table-agama',
                            template : 'custom',
                            column : head_religions,
                        })
                    ], 'h')
                },
                {
                    title: 'Statistik Data Pekerjaan',
                    label : 'ti-briefcase',
                    id : 'stats-pekerjaan',
                    content : concat('div',[
                        _piechart.init({
                            id : 'chart-pekerjaan',
                            height : 100,
                            title : 'Perbandingan Jumlah Data Pekerjaan',
                            labels : chart_occupations,
                        }),
                        _tables.render({
                            element : 'table-pekerjaan',
                            template : 'custom',
                            column : head_occupations,
                        })
                    ], 'h')
                },
            ],
        });

        let district_node = _districts._first;
        while (district_node !== undefined) {
            const district = district_node;
            stat_contents[district.name + '-' + district.id] = {
                education  : districtContent(district, head_educations, chart_educations, 'education', 'Pendidikan'),
                religion   : districtContent(district, head_religions, chart_religions, 'religion', 'Agama'),
                occupation : districtContent(district, head_occupations, chart_occupations, 'occupation', 'Pekerjaan'),
            }
            _tables.insert({
                element : 'table-district',
                column  : [
                    {
                        content : '<span class="font-weight-medium">' + district.name + '</span>',
                        click : function (e) {
                            window.touch = e.target;
                            document.getElementById('form-edit-ip-0').value   = district.id;
                            document.getElementById('form-edit-ip-1').value   = district.name;
                            document.getElementById('form-edit-ip-2').value   = district.size;
                            document.getElementById('form-delete-ip-0').value = district.id;
                            document.getElementById('form-delete-ip-1').value = district.name;
                            _card.content('stats-pendidikan', stat_contents[district.name + '-' + district.id].education);
                            _card.content('stats-agama', stat_contents[district.name + '-' + district.id].religion);
                            _card.content('stats-pekerjaan', stat_contents[district.name + '-' + district.id].occupation);
                            _card.show('districts-edit');
                        },
                        dblclick : function () {
                            _card.show('districts-edit');
                            _card.focus('districts-edit');
                        }
                    },
                    {content : '<span class="font-weight-medium">' + district.size + '</span> <small class="text-muted">m<sup>2<sup><small>'},
                    {content : '<span class="font-weight-medium">' + district.hamlets.length + '</span> <small class="text-muted">RT<small>'},
                    {content : '<span class="font-weight-medium">' + district.neighboors.length + '</span> <small>RW</small>'},
                    {content : '<span class="font-weight-medium">' + district.families.length + '</span> <small>keluarga</small>'},
                    {content : '<span class="font-weight-medium">' + district.citizens.length + '</span> <small>orang</small>'},
                ]
            });
            district_node  = district_node._next;
        }
        _card.content('stats-pendidikan', stat_contents[_districts._first.name + '-' + _districts._first.id].education);
        _card.content('stats-agama', stat_contents[_districts._first.name + '-' + _districts._first.id].religion);
        _card.content('stats-pekerjaan', stat_contents[_districts._first.name + '-' + _districts._first.id].occupation);
        _card.hide('districts-edit');
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
            name   : 'form-add-district',
            submit : 'form-add-ip-3-submit',
            fields : [
                {el: 'form-add-ip-0', name: 'name', validator:'name'},
                {el: 'form-add-ip-1', name: 'size', validator:'number'},
            ]
        });
        collections.set({
            name   : 'form-edit-district',
            submit : 'form-edit-ip-4-submit',
            fields : [
                {el: 'form-edit-ip-0', name:'id',},
                {el: 'form-edit-ip-1', name:'name', validator:'name', hasVal:true},
                {el: 'form-edit-ip-2', name:'size', validator:'number', hasVal:true},
            ]
        });
        collections.set({
            name   : 'form-delete-district',
            submit : 'form-delete-ip-3-dlt',
            fields : [
                {el: 'form-delete-ip-0', name:'id', hasVal:true},
                {el: 'form-delete-ip-1', name:'name', hasVal:true},
            ],
        });
        const add_submit_btn  = document.getElementById('form-add-ip-3-submit');
        const edit_submit_btn = document.getElementById('form-edit-ip-4-submit');
        const del_submit_btn  = document.getElementById('form-delete-ip-3-dlt');
        add_submit_btn.addEventListener('click', function (e) {
            _transition.in();
            const data = collections.collect('form-add-district');
            _response.post({async:false, url:'{{url('districtInsert')}}', data:data[0], file:data[1]});
            let output;
            if (_response.response._status) {
                if (_response.response.status === 'success') {
                    const res = _response.response;
                    stat_contents[res.name] = {
                        education  : districtContent({name:res.name, id:res.id}, head_educations, chart_educations, 'education', 'Pendidikan', true),
                        religion   : districtContent({name:res.name, id:res.id}, head_religions, chart_religions, 'religion', 'Agama', true),
                        occupation : districtContent({name:res.name, id:res.id}, head_occupations, chart_occupations, 'occupation', 'Pekerjaan', true),
                    }
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
                        element : 'table-district',
                        column  : [
                            {
                                content : '<span class="font-weight-medium">' + res.name + '</span>',
                                click : function (e) {
                                    window.touch = e.target;
                                    document.getElementById('form-edit-ip-0').value   = res.id;
                                    document.getElementById('form-edit-ip-1').value   = res.name;
                                    document.getElementById('form-edit-ip-2').value   = res.size;
                                    document.getElementById('form-delete-ip-0').value = res.id;
                                    document.getElementById('form-delete-ip-1').value = res.name;
                                    _card.content('stats-pendidikan', stat_contents[res.name].education);
                                    _card.content('stats-agama', stat_contents[res.name].religion);
                                    _card.content('stats-pekerjaan', stat_contents[res.name].occupation);
                                    _card.show('districts-edit');
                                },
                                dblclick : function () {
                                    _card.show('districts-edit');
                                    _card.focus('districts-edit');
                                }
                            },
                            {content : '<span class="font-weight-medium">' + res.size + '</span> <small class="text-muted">m<sup>2<sup><small>'},
                            {content : '<span class="font-weight-medium">' + 0 + '</span> <small class="text-muted">RT<small>'},
                            {content : '<span class="font-weight-medium">' + 0 + '</span> <small>RW</small>'},
                            {content : '<span class="font-weight-medium">' + 0 + '</span> <small>keluarga</small>'},
                            {content : '<span class="font-weight-medium">' + 0 + '</span> <small>orang</small>'},
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
            _card.focus('districts-data');
            _card.hide('districts-edit');
            _transition.out();
            _popup.content(output);
        });
        edit_submit_btn.addEventListener('click', function (e) {
            _transition.in();
            const data = collections.collect('form-edit-district');
            _response.post({async:false, url:'{{url('districtEdit')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    let touch_row = window.touch;
                    while (touch_row.nodeName !== 'TR') {
                        touch_row = touch_row.parentNode;
                    }
                    touch_row.firstChild.firstChild.innerHTML = res.name;
                    touch_row.children[1].firstChild.innerHTML = res.size;
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
            _card.focus('districts-data');
            _card.hide('districts-edit');
            _transition.out();
            _popup.content(output);
        });
        del_submit_btn.addEventListener('click', function (e) {
            _transition.in();
            const data = collections.collect('form-delete-district');
            _response.post({async:false, url:'{{url('districtDelete')}}', data:data[0], file:data[1]});
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
            document.getElementById('form-delete-ip-0').value = 'none';
            document.getElementById('form-delete-ip-1').value = 'none';
            _card.focus('districts-data');
            _card.hide('districts-edit');
            _transition.out();
            _popup.content(output);
        })
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
