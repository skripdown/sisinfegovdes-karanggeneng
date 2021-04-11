@extends('admin.template')

@section('title')
    Hak Akses
@endsection

@section('page-breadcrumb')
    Kelola Hak Akses
@endsection

@section('sub-breadcrumb')
    Halaman Kontrol Kelola Akses Pengguna {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="officer-content"></div>
    <div id="citizen-content"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        window._officer_roles       = {};
        window._officer_approvals   = {};
        window.touched_officer_id   = undefined;
        window.touched_officer_role = undefined;
        window.touched_officer_row  = undefined;
        window.touched_officer_col  = undefined;
        window.touched_citizen_row  = undefined;
        window.touched_citizen_col  = undefined;
        const pic_admin = '{{asset(env('PATH_ADMIN_PROFILE'))}}/';
        const pic_user  = '{{asset(env('PATH_CITIZEN_PROFILE'))}}/';
        const _user = _data.account;
        _user.refresh(function () {
            _response.get('{{url('/users').\App\Http\back\_UI::$FLAG_RELATION.'(citizen,officer,role,officer.approvals,officer.approvals.modifies)'}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function toggleSelect(element, value) {
            const selector = document.getElementById(element);
            const options  = selector.children;
            if (value) {
                options[1].selected = false;
                options[0].selected = true;
            }
            else {
                options[0].selected = false;
                options[1].selected = true;
            }
        }
        function touchOfficer(e) {
            let target = e.target;
            while (target.nodeName !== 'TD') {
                target = target.parentNode;
            }
            window.touched_officer_col = target;
            window.touched_officer_row = target.parentNode;
        }
        function touchCitizen(e) {
            let target = e.target;
            while (target.nodeName !== 'TD') {
                target = target.parentNode;
            }
            window.touched_citizen_col = target;
            window.touched_citizen_row = target.parentNode;
        }
        function canModify(officer) {
            if (officer.approvals.length === 0)
                return true;
            const approvals = officer.approvals;
            for (let i = 0; i < approvals.length; i++) {
                const type = approvals[i].type;
                if (type !== 'in')
                    return false;
            }

            return true;
        }
        function setTags(currentArr, approvalArr, role) {
            const currentJson  = {};
            const res    = [];
            const stay   = '<span class="bg-info font-12">';
            const change = '<span class="bg-info opacity-7 font-12">';
            const plus   = '<i class="ti-plus"></i> &nbsp;';
            const minus  = '<i class="ti-minus"></i> &nbsp;';

            for (let i = 0; i < currentArr.length; i++) {
                currentJson[currentArr[i]] = {};
            }

            if (role.civil) approvalArr.push('Kependudukan');
            if (role.employee) approvalArr.push('Kepegawaian');
            if (role.developer) approvalArr.push('Developer');
            if (role.publication) approvalArr.push('Publikasi');
            if (role.account) approvalArr.push('Akun');
            if (role.archive) approvalArr.push('Arsip');

            for (let i = 0; i < approvalArr.length; i++) {
                const item = approvalArr[i];
                if (currentJson[item] !== undefined) {
                    res.push(stay + item + '</span>');
                    currentJson[item] = undefined;
                }
                else {
                    res.push(change + plus + item + '</span>');
                }
            }

            for (let i = 0; i < currentArr.length; i++) {
                const cur = currentArr[i];
                if (currentJson[cur] !== undefined) {
                    res.push(change + minus + cur + '</span>');
                    currentJson[cur] = undefined;
                }
            }

            return res.reverse();
        }
        function setApprovals(approvals) {
            let approval;
            for (let i = 0; i < approvals.length; i++) {
                if (approvals[i].type === 'acc') {
                    approval = approvals[i];
                    break;
                }
            }
            const mods = approval.modifies;
            const res  = [];

            for (let i = 0; i < mods.length; i++) {
                if (mods[i].value === '1') {
                    if (mods[i].attribute === 'archive')
                        res.push('Arsip');
                    else if (mods[i].attribute === 'employee')
                        res.push('Kepegawaian')
                    else if (mods[i].attribute === 'civil')
                        res.push('Kependudukan')
                    else if (mods[i].attribute === 'developer')
                        res.push('Developer')
                    else if (mods[i].attribute === 'publication')
                        res.push('Publikasi')
                    else
                        res.push('Akun')
                }
            }

            return res;
        }
        function hasOfficer(officer) {
            if (officer == null)
                return false;
            return officer.regis === 'in';
        }

        _card.render({
            element : 'officer-content',
            items : [
                {
                    title : 'Akses Akun Kepegawaian',
                    label : 'ti-briefcase',
                    id : 'data-pegawai',
                    content : _tables.render({
                        element : 'officers-data',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Jenis'},
                            {content : 'Hak Akses Kelola'},
                            {content : 'Terakhir Diperbahrui'},
                            {content : 'Aksi'},
                        ],
                    })
                },
                {
                    title : 'Form Pembahruan Hak Akses',
                    label : 'ti-pencil',
                    id : 'edit-akses-pegawai',
                    content : _formfield.render({
                        element : 'form-edit',
                        width : ['sm-12','md-9','lg-6','xl-5'],
                        fields : [
                            {name:'id', type:'hidden'},
                            {name:'akses_modifikasi_akun', type:'select', values:[{value:'iya',label:'iya'},{value:'tidak',label:'tidak'}], header:'akses modifikasi Akun'},
                            {name:'akses_publikasi', type:'select', values:[{value:'iya',label:'iya'},{value:'tidak',label:'tidak'}], header:'akses publikasi media'},
                            {name:'akses_arsip', type:'select', values:[{value:'iya',label:'iya'},{value:'tidak',label:'tidak'}], header:'akses kelola arsip'},
                            {name:'akses_kependudukan', type:'select', values:[{value:'iya',label:'iya'},{value:'tidak',label:'tidak'}], header:'akses administrasi kependudukan'},
                            {name:'akses_kepegawaian', type:'select', values:[{value:'iya',label:'iya'},{value:'tidak',label:'tidak'}], header:'akses administrasi kepegawaian'},
                            {name:'akses_developer', type:'select', values:[{value:'iya',label:'iya'},{value:'tidak',label:'tidak'}], header:'akses administrasi kepegawaian'},
                            {},
                            {type:'submit', placeholder:'ubah'},
                        ],
                    })[0],
                }
            ]
        });
        _card.render({
            element : 'citizen-content',
            items : [
                {
                    title : 'Akses Akun Kependudukan',
                    label : 'ti-user',
                    id : 'data-pengguna',
                    content : _tables.render({
                        element : 'citizens-data',
                        template : 'custom',
                        column : [
                            {content : 'Profil'},
                            {content : 'Umur'},
                            //{content : 'Laporan'},
                            {content : 'Hak Akses'},
                            {content : 'Aksi'},
                        ],
                    })
                },
            ]
        });
        let node_iter = _user._first;
        while (node_iter !== undefined) {
            const user     = node_iter;
            const officer  = user.officer;
            if (hasOfficer(officer)) {
                let status;
                let btn = _btn_group.make([
                    _btn.render({
                        size : 'sm',
                        operate : 'ubah',
                        type : 'success',
                        title : 'mengubah hak akses',
                        content : '<i class="ti-pencil font-weight-medium"></i>',
                        fun : function (e) {
                            if (no_chief && can_modify) {
                                touchOfficer(e);
                                window.touched_officer_id   = officer.id;
                                window.touched_officer_role = user.role;
                                document.getElementById('form-edit-ip-0').value = role.id;
                                toggleSelect('form-edit-ip-1', mod_account);
                                toggleSelect('form-edit-ip-2', mod_publication);
                                toggleSelect('form-edit-ip-3', mod_archive);
                                toggleSelect('form-edit-ip-4', mod_civil);
                                toggleSelect('form-edit-ip-5', mod_employee);
                                toggleSelect('form-edit-ip-6', mod_developer);
                                _card.show('edit-akses-pegawai');
                                _card.focus('edit-akses-pegawai');
                            }
                        }
                    }),
                ]);
                btn.setAttribute('class', btn.getAttribute('class') + ' bg-danger');
                btn.setAttribute('style', 'margin:auto; width:30%;');
                let mod_account     = false,
                    mod_civil       = false,
                    mod_employee    = false,
                    mod_publication = false,
                    mod_archive     = false,
                    mod_developer   = false,
                    no_chief        = true,
                    can_modify      = canModify(officer);
                if (officer.status === 'asn') status = 'Aparatur Sipil Negara';
                else status = 'Honorer';
                const role = user.role;
                const roleList = [];
                if (role.chief) {
                    no_chief = false;
                    roleList.push('Kepala Desa');
                    btn = '';
                }
                if (role.civil) {
                    roleList.push('Kependudukan');
                    mod_civil = true;
                }
                if (role.employee) {
                    roleList.push('Kepegawaian');
                    mod_employee = true;
                }
                if (role.developer) {
                    roleList.push('Developer');
                    mod_developer = true;
                }
                if (role.publication) {
                    roleList.push('Publikasi');
                    mod_publication = true;
                }
                if (role.account) {
                    roleList.push('Akun');
                    mod_account = true;
                }
                if (role.archive) {
                    roleList.push('Arsip');
                    mod_archive = true;
                }
                window._officer_roles[officer.id] = roleList;
                let contentTags;
                if (!can_modify) {
                    btn = '<p class="text-muted font-12 text-wrap text-center" style="width: 5vw">menunggu verifikasi kepala</p>';
                    window._officer_approvals[officer.id] = setApprovals(officer.approvals);
                    contentTags = setTags(roleList, _officer_approvals[officer.id], role);
                } else
                    contentTags = roleList;
                _tables.insert({
                    element : 'officers-data',
                    column  : [
                        {
                            content  : '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="'+ pic_admin + user.pic +'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+user.name+'</h5><span class="text-muted font-14">'+officer.identity+'</span></div></div>',
                            click    : function (e) {
                                if (no_chief && can_modify) {
                                    window.touched_officer_id   = officer.id;
                                    window.touched_officer_role = user.role;
                                    touchOfficer(e);
                                    document.getElementById('form-edit-ip-0').value = role.id;
                                    toggleSelect('form-edit-ip-1', mod_account);
                                    toggleSelect('form-edit-ip-2', mod_publication);
                                    toggleSelect('form-edit-ip-3', mod_archive);
                                    toggleSelect('form-edit-ip-4', mod_civil);
                                    toggleSelect('form-edit-ip-5', mod_employee);
                                    toggleSelect('form-edit-ip-6', mod_developer);
                                    _card.show('edit-akses-pegawai');
                                }
                            },
                            dblclick : function () {
                                if (no_chief && can_modify)
                                    _card.focus('edit-akses-pegawai');
                            },
                        },
                        {content    : '<span class="font-weight-medium">' + status + '</span>'},
                        {
                            content : _taglist.render({
                                element   : 'access-' + officer.id,
                                keys      : contentTags,
                                opt_class : 'pl-2 pr-2 text-capitalize font-12 bg-info',
                            })
                        },
                        {content : '<span class="font-weight-medium">' + _date.convert_created_at(role.updated_at, '<small class="text-muted"> WIB</small>', '<small class="text-muted pr-1">tanggal </small>', '<small class="text-muted pl-4 pr-1"> pukul </small>') + '</span>'},
                        {
                            content : btn
                        }
                    ],
                });
            } else {
                const citizen = user.citizen;
                const age     = _date.age(citizen.year_birth, citizen.month_birth, citizen.day_birth);
                let img, access, btn_block, btn_unblock, btn_temp;
                btn_block     = _btn_group.make([
                    _btn.render({
                        size : 'sm',
                        operate : 'blokir',
                        type : 'secondary',
                        title : 'blokir',
                        content : 'blokir',
                        fun : function (e) {
                            _transition.in();
                            _response.post({async:false, url:'{{url('userUnblock')}}', data:{id:user.id}, file:new FormData()});
                            const res = _response.response;
                            if (res._status && res.status === 'success') {
                                console.log('block');
                                let td = e.target;
                                while (td.nodeName !== 'TD') {td = td.parentNode;}
                                let st = td.previousSibling;
                                td.innerHTML = '';
                                td.appendChild(btn_unblock);
                                st.innerHTML = '<span class="font-weight-medium text-danger">diblokir</span>';
                            }
                            _transition.out();
                        }
                    }),
                ]);
                btn_unblock   = _btn_group.make([
                    _btn.render({
                        size : 'sm',
                        operate : 'buka',
                        type : 'success',
                        title : 'buka',
                        content : 'buka',
                        fun : function (e) {
                            _transition.in();
                            _response.post({async:false, url:'{{url('userBlock')}}', data:{id:user.id}, file:new FormData()});
                            const res = _response.response;
                            if (res._status && res.status === 'success') {
                                console.log('unblock');
                                let td = e.target;
                                while (td.nodeName !== 'TD') {td = td.parentNode;}
                                let st = td.previousSibling;
                                td.innerHTML = '';
                                td.appendChild(btn_block);
                                st.innerHTML = '<span class="font-weight-medium text-success">diperbolehkan</span>';
                            }
                            _transition.out();
                        }
                    }),
                ]);
                if (user.officer == null) img = pic_user + user.pic;
                else img = pic_admin + user.pic;
                if (user.usable) {
                    btn_temp = btn_block;
                    access = '<span class="font-weight-medium text-success">diperbolehkan</span>';
                }
                else {
                    btn_temp = btn_unblock;
                    access = '<span class="font-weight-medium text-danger">diblokir</span>';
                }
                _tables.insert({
                    element : 'citizens-data',
                    column  : [
                        {
                            content: '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="' + img + '" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+citizen.name+'</h5><span class="text-muted font-14">'+citizen.identity+'</span></div></div>',
                        },
                        {content : '<span class="font-weight-medium">' + age + '</span> <small class="text-muted">tahun</small>'},
                        {content : access},
                        {content : btn_temp}
                    ],
                });
            }
            node_iter  = node_iter._next;
        }
        collections.set({
            name   : 'form-modify-access',
            submit : 'form-edit-ip-8-submit',
            fields : [
                {el: 'form-edit-ip-0', name: 'id', hasVal:true},
                {el: 'form-edit-ip-1', name: 'account',},
                {el: 'form-edit-ip-2', name: 'publication',},
                {el: 'form-edit-ip-3', name: 'archive',},
                {el: 'form-edit-ip-4', name: 'civil',},
                {el: 'form-edit-ip-5', name: 'employee',},
                {el: 'form-edit-ip-6', name: 'developer',},
            ],
        });
        document.getElementById('form-edit-ip-8-submit').addEventListener('click', function () {
            _transition.in();
            const data = collections.collect('form-modify-access');
            _response.post({async : false, url:'{{url('roleUpdate')}}', data:data[0], file:data[1]});
            const res = _response.response;
            let output;
            if (res._status) {
                if (res.status === 'success') {
                    const touch_id    = window.touched_officer_id;
                    const touch_role  = window.touched_officer_role;
                    window._officer_approvals[touch_id] = setApprovals([res.approval]);
                    const contentTags = setTags(window._officer_roles[touch_id], _officer_approvals[touch_id], touch_role);
                    const row         = window.touched_officer_row;
                    const col         = window.touched_officer_col;
                    col.innerHTML     = '<p class="text-muted font-12 text-wrap text-center" style="width: 5vw">menunggu verifikasi kepala</p>';
                    row.children[2].innerHTML = '';
                    row.children[2].appendChild(_taglist.render({
                        element   : 'access-' + touch_id,
                        keys      : contentTags,
                        opt_class : 'pl-2 pr-2 text-capitalize font-12 bg-info',
                    }));
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
            _card.focus('data-pegawai');
            _card.hide('edit-akses-pegawai');
            _transition.out();
            _popup.content(output);
        });
        _card.hide('edit-akses-pegawai');
        _popup.init({element : 'popup-notification', align : 'center',});
        _transition.out();
    </script>
@endsection
