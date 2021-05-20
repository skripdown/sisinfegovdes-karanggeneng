@extends('admin.template')

@section('title')
    Mutasi Masuk
@endsection

@section('page-breadcrumb')
    Mutasi Kepegawaian
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Mutasi Masuk Kepegawaian {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-mutasi"></div>
    <div id="history-in"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        window.temp_click_citizen = undefined;
        window.temp_click_row     = undefined;
        const uppercase = function (text) {
            const words = text.split(' ');
            let result  = '';
            for (let i = 0; i < words.length; i++) {
                result += (words[i][0].toUpperCase() + words[i].substring(1));
                if (i !== words.length - 1)
                    result += ' ';
            }

            return result;
        }
        const _ASN         = {
            asn     : undefined,
            honorer : undefined,
            init    : function () {
                function makeOption(options) {
                    const arr = [];
                    for (let i = 0; i < options.length; i++) {
                        const option = document.createElement('option');
                        const value  = options[i];
                        const text   = uppercase(options[i]);
                        option.setAttribute('value', value);
                        option.innerHTML = text;
                        arr.push(option);
                    }

                    return arr;
                }
                function makeGroup(name, options) {
                    const group = document.createElement('optgroup');
                    group.setAttribute('label', uppercase(name));
                    for (let i = 0; i < options.length; i++) {
                        group.appendChild(options[i]);
                    }

                    return group;
                }
                _ASN.asn = [
                    makeGroup('gol IV', makeOption(['pembina utama', 'pembina utama madya', 'pembina utama muda', 'pembina tingkat i', 'pembina'])),
                    makeGroup('gol III', makeOption(['penata tingkat i', 'penata', 'penata muda tingkat i', 'penata muda'])),
                    makeGroup('gol II', makeOption(['pengatur tingkat i', 'pengatur', 'pengatur muda tingkat i', 'pengatur muda'])),
                    makeGroup('gol I', makeOption(['juru tingkat i', 'juru', 'juru muda tingkat i', 'juru muda'])),
                ];
                _ASN.honorer = makeOption(['tidak ada'])[0];
            }
        };
        const pic_user     = '{{asset(env('PATH_CITIZEN_PROFILE'))}}/';
        const pic_admin    = '{{asset(env('PATH_ADMIN_PROFILE'))}}/';
        const data         = {!! $data !!};
        const _in_mutation = {};
        const _citizen     = _data.citizen;
        _citizen.refresh(function () {
            _response.get('{{url('/citizens'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(user,citeducation,citeducation.education,citoccupation,citoccupation.occupation,user.officer)')}}',false);
            return _response.response;
        });
        _ASN.init();
    </script>
@endsection

@section('script-body')
    <script>
        function asnRankInput(select, active=false) {
            if (typeof select === 'string')
                select = document.getElementById(select);
            select.innerHTML = '';
            if (active) {
                const groups = _ASN.asn;
                for (let i = 0; i < groups.length; i++) {
                    select.appendChild(groups[i]);
                }
            }
            else {
                select.appendChild(_ASN.honorer);
            }
        }
        function rank(rank) {
            const rank_arr = {!! json_encode(\App\Models\Officer::$rank) !!};
            if (rank_arr[rank].set === undefined)
                return ['-', '-', '-'];
            return [rank, rank_arr[rank].set,rank_arr[rank].room];
        }
        function initRequest(data) {
            const result = {};
            if (data.length === 0)
                return result;
            for (let i = 0; i < data.length; i++) {
                const request = {};
                const temp    = data[i];
                const mods    = temp.modifies;
                let id        = undefined;
                for (let j = 0; j < mods.length; j++) {
                    const modify = mods[j];
                    if (modify.attribute === 'id') {
                        id = modify.value;
                    }
                    request[modify.attribute] = modify.value;
                }
                result[id]    = request;
            }

            return result;
        }
        function onRequest(user) {
            return _in_request[user.id] !== undefined
        }
        function isOfficer(user) {
            return user.officer != null;
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
            element : 'data-mutasi',
            items : [
                {
                    title : 'Data Permintaan Mutasi Masuk',
                    label : 'ti-user',
                    id : 'data-mutasi-masuk',
                    content : _tables.render({
                        element : 'table-mutation',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Gaji'},
                        ]
                    })
                },
                {
                    title : 'Data Penduduk',
                    label : 'ti-plus',
                    id : 'data-penduduk',
                    content : _tables.render({
                        element : 'table-penduduk',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Pendidikan'},
                            {content : 'Pekerjaan'},
                            {content : 'Aksi'},
                        ]
                    })
                },
                {
                    title : 'Form Mutasi Masuk Kepegawaian',
                    label : 'ti-pencil',
                    id : 'form-mutasi-masuk',
                    content : _formfield.render({
                        element : 'form-insert-mutasi',
                        width : ['sm-12','md-9','lg-6','xl-5'],
                        fields : [
                            {name:'id', type:'hidden'},
                            {name:'nama', type: 'text', placeholder: 'nama pegawai', readOnly: true, value:'nama pegawai', header: 'Nama Pegawai'},
                            {name:'nik', type: 'text', placeholder: 'NIK pegawai', readOnly: true, value:'NIK pegawai', header:'Nomor Induk Kependudukan'},
                            {name:'nip', type: 'text', placeholder: 'NIP', value:'NIP', header: 'Nomor Induk Pegawai'},
                            {name:'jenis',type : 'select', values:[{value:'asn',label:'PNS'},{value:'honor',label:'Honorer'}], header:'Jenis'},
                            {name:'rank',type : 'select', values:[{value:'asn',label:'PNS'},{value:'honor',label:'Honorer'}], header:'Pangkat Golongan ASN'},
                            {name:'gaji', type: 'text', placeholder: 'gaji', value:'gaji', header:'gaji'},
                            {name:'type', type:'hidden', value: 'in'},
                            {},
                            {type:'submit', placeholder: 'tambah'},
                        ]
                    })[0]
                },
            ],
        });
        _card.render({
            element : 'history-in',
            items   : [
                {
                    title : 'Riwayat Mutasi Masuk',
                    label : 'ti-user',
                    id : 'his-mutasi-masuk',
                    content : _tables.render({
                        element : 'table-his-in',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Masuk Pada'},
                        ]
                    })
                },
            ],
        });

        const _in_request = initRequest(data);

        let node_iter = _citizen._first;
        while (node_iter !== undefined) {
            const citizen = node_iter;
            const user    = citizen.user;

            if (onRequest(user)) {
                _in_mutation[user.id] = citizen;
                const request = _in_request[user.id];
                const roomSet = rank(request.rank);

                _tables.insert({
                    element : 'table-mutation',
                    column  : [
                        {
                            content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_user + citizen.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+citizen.name+'</h5><span class="text-muted font-14">'+citizen.identity+'</span></div></div>',
                        },
                        {content : '<span class="font-weight-medium"><small class="text-muted">Rp.</small> ' + officerSalary(request.salary) + '</span>'},
                    ],
                });
            } else {
                if (!isOfficer(user)) {
                    _tables.insert({
                        element : 'table-penduduk',
                        column  : [
                            {
                                content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_user + citizen.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+citizen.name+'</h5><span class="text-muted font-14">'+citizen.identity+'</span></div></div>',
                            },
                            {content : '<span class="font-weight-medium">' + citizen.citeducation.education.name + '</span>'},
                            {content : '<span class="font-weight-medium">' + citizen.citoccupation.occupation.name + '</span>'},
                            {
                                content : _btn_group.make([
                                    _btn.render({
                                        size : 'sm',
                                        operate : 'tambahkan',
                                        type : 'success',
                                        title : 'tambahkan sebagai pegawai',
                                        content : '<i class="ti-plus font-weight-bold"></i> &nbsp;tambahkan',
                                        fun : function (e) {
                                            document.getElementById('form-insert-mutasi-ip-0').value = user.id;
                                            document.getElementById('form-insert-mutasi-ip-1').value = citizen.name;
                                            document.getElementById('form-insert-mutasi-ip-2').value = citizen.identity;
                                            document.getElementById('form-insert-mutasi-ip-3').value = '';
                                            document.getElementById('form-insert-mutasi-ip-6').value = '1000000';
                                            _card.show('form-mutasi-masuk');
                                            _card.focus('form-mutasi-masuk');
                                            const target = e.target;
                                            window.temp_click_citizen = citizen;
                                            window.temp_click_row     = target;
                                        }
                                    }),
                                ]),
                            },
                        ],
                    });
                } else {
                    if (user.officer.regis === 'in') {
                        const officer = user.officer;
                        const roomSet = rank(officer.rank);
                        _tables.insert({
                            element : 'table-his-in',
                            column  : [
                                {
                                    content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_admin + user.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+citizen.name+'</h5><span class="text-muted font-14">'+officer.identity+'</span></div></div>',
                                },
                                {content : '<span class="font-weight-medium">' + _date.convert_created_at(officer.created_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                            ],
                        });
                    }
                }
            }

            node_iter = node_iter._next;
        }
        asnRankInput(document.getElementById('form-insert-mutasi-ip-5'), true);
        document.getElementById('form-insert-mutasi-ip-4').addEventListener('change', function () {
            const selSource = document.getElementById('form-insert-mutasi-ip-4');
            const selTar    = document.getElementById('form-insert-mutasi-ip-5');
            if (selSource.value === 'asn')
                asnRankInput(selTar, true);
            else
                asnRankInput(selTar, false);
        });
        collections.set({
            name   : 'form-insert-mutasi',
            submit : 'form-insert-mutasi-ip-9-submit',
            fields : [
                {el: 'form-insert-mutasi-ip-0', name: 'id', hasVal:true},
                {el: 'form-insert-mutasi-ip-1', name: 'name', hasVal:true},
                {el: 'form-insert-mutasi-ip-3', name: 'identity', hasVal:true, validator:'number'},
                {el: 'form-insert-mutasi-ip-4', name: 'status_',},
                {el: 'form-insert-mutasi-ip-5', name: 'rank',},
                {el: 'form-insert-mutasi-ip-6', name: 'salary', hasVal:true, validator:'number'},
                {el: 'form-insert-mutasi-ip-7', name: 'type', hasVal:true},
            ],
        });
        document.getElementById('form-insert-mutasi-ip-9-submit').addEventListener('click', function () {
            _transition.in();
            const citizen = window.temp_click_citizen;
            const data = collections.collect('form-insert-mutasi');
            _response.post({async:false, url:'{{url('officerMutate')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    let node  = window.temp_click_row;
                    const ctz = window.temp_click_citizen;
                    const roomSet = rank(res.rank);
                    while (node.nodeName !== 'TR') {
                        node = node.parentNode;
                    }
                    const par = node.parentNode;
                    par.removeChild(node);
                    _tables.insert({
                        element : 'table-mutation',
                        column  : [
                            {
                                content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_user + ctz.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+ctz.name+'</h5><span class="text-muted font-14">'+ctz.identity+'</span></div></div>',
                            },
                            {content : '<span class="font-weight-medium"><small class="text-muted">Rp.</small> ' + officerSalary(res.salary) + '</span>'},
                        ],
                    });
                    if (_in_mutation[citizen.user.id] === undefined)
                        _in_mutation[citizen.user.id] = citizen;
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
            _card.focus('data-mutasi-masuk');
            _card.hide('form-mutasi-masuk');
            _transition.out();
            _popup.content(output);
        });

        _card.hide('form-mutasi-masuk');
        _popup.init({element : 'popup-notification', align : 'center',});
        _transition.out();
    </script>
@endsection
