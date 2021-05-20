@extends('admin.template')

@section('title')
    Kepegawaian
@endsection

@section('page-breadcrumb')
    Kepegawaian
@endsection

@section('sub-breadcrumb')
    Halaman Administrasi Kepegawaian {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-officers"></div>

@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _APP_INIT  = function (approvals) {
            const disabled = {};
            let node_iter = approvals._first;
            while (node_iter !== undefined) {
                const approval = node_iter;
                if (approval.type !== 'in')
                    disabled[approval.officer_id] = approval;
                node_iter = node_iter._next;
            }

            return disabled;
        }
        const uppercase  = function (text) {
            const words = text.split(' ');
            let result  = '';
            for (let i = 0; i < words.length; i++) {
                result += (words[i][0].toUpperCase() + words[i].substring(1));
                if (i !== words.length - 1)
                    result += ' ';
            }

            return result;
        }
        const _ASN       = {
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
        const pic_admin  = '{{asset(env('PATH_ADMIN_PROFILE'))}}/';
        const _officers  = _data.officer;
        const _approvals = _data.approval;
        _officers.refresh(function () {
            _response.get('{{url('/officers'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(user,user.citizen,user.citizen.citeducation,user.citizen.citeducation.education,approvals,approvals.modifies)')}}',false);
            return _response.response;
        });
        _approvals.refresh(function () {
            _response.get('{{url('/approvals'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(modifies,officer)')}}',false);
            return _response.response;
        });
        const _disabled   = _APP_INIT(_approvals);
        _ASN.init();
    </script>
@endsection

@section('script-body')
    <script>
        function setOfficerMod(officer) {
            document.getElementById('form-edit-ip-0').value = officer.id;
            document.getElementById('form-edit-ip-1').value = officer.user.name;
            document.getElementById('form-edit-ip-2').value = officer.user.identity;
            document.getElementById('form-edit-ip-3').value = officer.identity;
            document.getElementById('form-edit-ip-6').value = officer.salary;
            const selectStatus = document.getElementById('form-edit-ip-4');
            const statusChild  = selectStatus.children;
            for (let i = 0; i < statusChild.length; i++) {
                if (statusChild[i].value === officer.status) {
                    statusChild[i].selected = true;
                    break;
                }
            }
            if (officer.status === 'asn') {
                asnRankInput('form-edit-ip-5', true);
                const selectRank = document.getElementById('form-edit-ip-5');
                const groups     = selectRank.children;
                let stop         = false;
                for (let i = 0; i < groups.length; i++) {
                    if (stop) break;
                    const options = groups[i].children;
                    for (let j = 0; j < options.length; j++) {
                        if (officer.rank === options[j].value) {
                            options[j].selected = true;
                            stop = true;
                            break;
                        }
                    }
                }
            }
            else
                asnRankInput('form-edit-ip-5');

        }
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
        function officerStatus(officer) {
            if (officer.status === 'asn')
                return 'Aparatur Sipil Negara';
            return 'Honorer';
        }
        function officerSalary(officer) {
            const salary = (officer.salary + '').split('');
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
        function officerApproval(officer) {
            if (enabled(officer)) {
                if (officer.status === 'asn') {
                    return _btn_group.make([
                        _btn.render({
                            operate : 'mutasi',
                            type : 'warning',
                            size : 'sm',
                            title : 'mutasi keluar',
                            content : '<span class="text-white">mutasi</span>',
                            fun : function (e) {
                                _transition.in();
                                const event = e;
                                _response.post({async:false, url:'{{url('mutate')}}', data:{id:officer.user.id,type:'out'}, file: new FormData()});
                                const res = _response.response;
                                let output;
                                if (res._status) {
                                    if (res.status === 'success') {
                                        let target = event.target;
                                        while (target.nodeName !== 'TD') {
                                            target = target.parentNode;
                                        }
                                        target.innerHTML = '<small class="text-muted text-center">menunggu verifikasi kepala</small>';
                                        officer.requestmutate = res.requestmutate;
                                        officer.approval = res.approval;
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
                                _card.focus('officer-data');
                                _card.hide('officer-form');
                                _transition.out();
                                _popup.content(output);
                            }
                        }),
                        _btn.render({
                            operate : 'edit',
                            type : 'success',
                            size : 'sm',
                            title : 'ubah data',
                            content : '<i class="ti-pencil"></i>',
                            fun : function (e) {
                                let target = e.target;
                                while (target.nodeName !== 'TD') {
                                    target = target.parentNode;
                                }
                                window.focused = target;
                                window.focused_officer = officer;
                                setOfficerMod(officer);
                                _card.show('officer-form');
                                _card.focus('officer-form');
                            }
                        }),
                    ]);
                }
                return _btn_group.make([
                    _btn.render({
                        operate : 'edit',
                        type : 'success',
                        size : 'sm',
                        title : 'ubah data',
                        content : '<i class="ti-pencil"></i>',
                        fun : function (e) {
                            let target = e.target;
                            while (target.nodeName !== 'TD') {
                                target = target.parentNode;
                            }
                            window.focused = target;
                            window.focused_officer = officer;
                            setOfficerMod(officer);
                            _card.show('officer-form');
                            _card.focus('officer-form');
                        }
                    }),
                    _delete.render(function (e) {
                        _transition.in();
                        let target = e.target;
                        while (target.nodeName !== 'TD') {
                            target = target.parentNode;
                        }
                        window.focused = target;
                        window.focused_officer = officer;
                        _response.post({async:false, url:'{{url('officerDelete')}}', data:{id:officer.id}, file:new FormData()});
                        const res = _response.response;
                        let output;
                        if (res._status) {
                            if (res.status === 'success') {
                                window.focused.innerHTML = '<small class="text-muted text-center">menunggu verifikasi kepala</small>';
                                window.focused_officer.approval = res.approval;
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
                        _card.focus('officer-data');
                        _card.hide('officer-form');
                        _transition.out();
                        _popup.content(output);
                    }),
                ]);
            }
            return '<small class="text-muted text-center">menunggu verifikasi kepala</small>';
        }
        function enabled(officer) {
            return _disabled[officer.user.id] === undefined;
        }

        _card.render({
            element : 'data-officers',
            items : [
                {
                    title : 'Data Pegawai',
                    label : 'ti-user',
                    id : 'officer-data',
                    content : _tables.render({
                        element : 'officer-table',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Pendidikan'},
                            {content : 'Gaji'},
                            {content : 'Lama Kerja'},
                            {content : 'Aksi'},
                        ],
                    })
                },
                {
                    title : 'Ubah data pegawai',
                    label : 'ti-pencil-alt',
                    id : 'officer-form',
                    content : _formfield.render({
                        element : 'form-edit',
                        width : ['sm-12','md-9','lg-6','xl-5'],
                        fields : [
                            {name:'id', type:'hidden'},
                            {name:'nama', type: 'text', placeholder: 'nama pegawai', readOnly: true, value:'nama pegawai', header: 'Nama Pegawai'},
                            {name:'nik', type: 'text', placeholder: 'NIK pegawai', readOnly: true, value:'NIK pegawai', header:'Nomor Induk Kependudukan'},
                            {name:'nip', type: 'text', placeholder: 'NIP', value:'NIP', header: 'Nomor Induk Pegawai'},
                            {name:'jenis',type : 'select', values:[{value:'asn',label:'PNS'},{value:'honor',label:'Honorer'}], header:'Jenis'},
                            {name:'rank',type : 'select', values:[{value:'asn',label:'PNS'},{value:'honor',label:'Honorer'}], header:'Pangkat Golongan ASN'},
                            {name:'gaji', type: 'text', placeholder: 'gaji', value:'gaji', header:'gaji'},
                            {},
                            {type:'submit', placeholder: 'ubah'},
                        ]
                    })[0]
                }
            ]
        });
        document.getElementById('form-edit-ip-4').addEventListener('change', function () {
            const selSource = document.getElementById('form-edit-ip-4');
            const selTar    = document.getElementById('form-edit-ip-5');
            if (selSource.value === 'asn')
                asnRankInput(selTar, true);
            else
                asnRankInput(selTar, false);
        });
        collections.set({
            name   : 'form-edit-officer',
            submit : 'form-edit-ip-8-submit',
            fields : [
                {el: 'form-edit-ip-0', name: 'id', hasVal:true},
                {el: 'form-edit-ip-3', name: 'identity', hasVal:true, validator:'number'},
                {el: 'form-edit-ip-4', name: 'status',},
                {el: 'form-edit-ip-5', name: 'rank',},
                {el: 'form-edit-ip-6', name: 'salary', hasVal:true, validator:'number'},
            ],
        });
        document.getElementById('form-edit-ip-8-submit').addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-edit-officer');
            _response.post({async:false, url:'{{url('officerEdit')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    window.focused.innerHTML = '<small class="text-muted text-center">menunggu verifikasi kepala</small>';
                    window.focused_officer.approval = res.approval;
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
            _card.focus('officer-data');
            _card.hide('officer-form');
            _transition.out();
            _popup.content(output);
        });
        let node_iter = _officers._first;
        while (node_iter !== undefined) {
            const officer = node_iter;
            const user    = officer.user;
            const citizen = user.citizen;
            _tables.insertFirst({
                element : 'officer-table',
                column  : [
                    {
                        content  : '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+pic_admin+user.pic+'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+citizen.name+'</h5><span class="text-muted font-14">'+officer.identity+'</span></div></div>',
                        click    : function (e) {
                            if (enabled(officer)) {
                                window.focused_officer = officer;
                                setOfficerMod(officer);
                                let tar = e.target;
                                while (tar.nodeName !== 'TD') {
                                    tar = tar.parentNode;
                                }
                                window.focused = tar.nextSibling.nextSibling.nextSibling.nextSibling;
                                _card.show('officer-form');
                            }
                            else {
                                _card.hide('officer-form');
                            }
                        },
                        dblclick : function () {
                            if (enabled(officer)) {
                                _card.focus('officer-form');
                            }
                            else {
                                _card.hide('officer-form');
                            }
                        }
                    },
                    {content: citizen.citeducation.education.name},
                    {content: '<small class="text-muted font-weight-medium">Rp.</small> '+officerSalary(officer)},
                    {content: _date.timestamp_old(officer.created_at)},
                    {content: officerApproval(officer)},
                ],
            });
            node_iter = node_iter._next;
        }
        _card.hide('officer-form');
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
    <script>
        //revision addition
        document.getElementById('form-edit-ip-4').setAttribute('class',document.getElementById('form-edit-ip-4').getAttribute('class') + ' d-none');
        document.getElementById('form-edit-ip-4').previousElementSibling.previousElementSibling.setAttribute('class',document.getElementById('form-edit-ip-4').previousElementSibling.previousElementSibling.getAttribute('class') + ' d-none');
        document.getElementById('form-edit-ip-5').setAttribute('class',document.getElementById('form-edit-ip-5').getAttribute('class') + ' d-none');
        document.getElementById('form-edit-ip-5').previousElementSibling.previousElementSibling.setAttribute('class',document.getElementById('form-edit-ip-5').previousElementSibling.previousElementSibling.getAttribute('class') + ' d-none');
    </script>
@endsection
