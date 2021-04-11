@extends('admin.template')

@section('title')
    Persetujuan
@endsection

@section('page-breadcrumb')
    Data Persetujuan
@endsection

@section('sub-breadcrumb')
    Halaman Permintaan Persetujuan {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="card-approvals"></div>
    <div id="card-details"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const data = {!! $data !!};
        const _citizens = _data.citizen, _officers = _data.officer;

    </script>
@endsection

@section('script-body')
    <script>
        function approvalTitle(approval) {
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

            let id_user = '';
            if (approval.type === 'in') {
                const mods = approval.modifies;
                let identity = '', status = '', rank = '', salary = '';
                for (let i = 0; i < mods.length; i++) {
                    if (mods[i].attribute === 'identity')
                        identity = mods[i].value;
                    else if (mods[i].attribute === 'status')
                        status = mods[i].value;
                    else if (mods[i].attribute === 'rank')
                        rank = mods[i].value;
                    else if (mods[i].attribute === 'salary')
                        salary = mods[i].value;
                    else if (mods[i].attribute === 'id')
                        id_user = parseInt(mods[i].value);
                }

                if (status === 'ans')
                    status = ' <span class="font-weight-medium text-success">ANS</span> ';
                else
                    status = ' <span class="font-weight-medium text-success">Honorer</span> ';
                rank       = ' <span class="text-capitalize font-weight-medium text-success">' + rank + '</span> ';
                salary     = ' <small class="text-muted">Rp. </small> <span class="font-weight-medium text-success">' + officerSalary(salary) + '</span>';

                const result = '<p class="text-wrap" style="max-width: 40vw">Permintaan mutasi masuk pegawai baru dengan <small class="text-muted">NIP</small> <span class="font-weight-medium text-success">' + identity + '</span> <small class="text-muted">status</small>' +
                               status + '<small class="text-muted">pangkat</small>' + rank + 'dan <small class="text-muted">gaji</small>' +
                               salary + '</p>';

                return [result, id_user];
            }
            else if (approval.type === 'out' || approval.type === 'exp') {
                const mods = approval.modifies;
                let text;
                if (approval.type === 'out')
                    text = 'mutasi keluar';
                else
                    text = 'pensiun';
                let identity = '', status = '', rank = '', timestamp_old = '', name = '';
                for (let i = 0; i < mods.length; i++) {
                    if (mods[i].attribute === 'identity')
                        identity = mods[i].value;
                    else if (mods[i].attribute === 'name')
                        name = mods[i].value;
                    else if (mods[i].attribute === 'rank')
                        rank = mods[i].value;
                    else if (mods[i].attribute === 'timestamp_old')
                        timestamp_old = mods[i].value;
                    else if (mods[i].attribute === 'status')
                        status = mods[i].value;
                    else if (mods[i].attribute === 'id')
                        id_user = mods[i].value;
                }
                if (status === 'ans')
                    status = ' <span class="font-weight-medium text-success">ANS</span> ';
                else
                    status = ' <span class="font-weight-medium text-success">Honorer</span> ';
                rank       = ' <span class="text-capitalize font-weight-medium text-success">' + rank + '</span> ';
                timestamp_old = ' <span class="font-weight-medium text-success">' + _date.timestamp_old(timestamp_old.replace(' ','T')) + '</span> ';

                const result = '<p class="text-wrap" style="max-width: 40vw">Permintaan ' + text + ' pegawai dengan <small class="text-muted">NIP</small> <span class="font-weight-medium text-success">' + identity + '<small class="text-muted">(</small>' + name + '<small class="text-muted">)</small></span>  <small class="text-muted">status</small>' +
                    status + '<small class="text-muted">pangkat</small>' + rank + ' dan <small class="text-muted">lama kerja </small>' + timestamp_old + '</p>';

                return [result, id_user];
            }
            else if (approval.type === 'mod') {
                const mods = approval.modifies;
                let result = 'Permintaan mengubah data kepegawaian oleh <span class="text-success font-weight-medium">' + approval.officer.identity + '<small> (' + approval.officer.user.name + ')</small></span> dengan ';
                for (let i = 0; i < mods.length; i++) {
                    if (i !== 0 && mods[i].attribute !== 'id')
                        result += ' ,'
                    if (mods[i].attribute === 'salary')
                        result += 'gaji <small class="text-muted">Rp. </small><span class="font-weight-medium text-success">' + officerSalary(approval.officer.salary) + '</span> menjadi <small class="text-muted">Rp. </small><span class="font-weight-medium text-success"> ' + officerSalary(mods[i].value) + '</span>';
                    else if (mods[i].attribute === 'id')
                        id_user = parseInt(mods[i].value);
                    else if (mods[i].attribute === 'rank')
                        result += 'pangkat jabatan <span class="font-weight-medium text-success">' + approval.officer.rank + '</span> menjadi <span class="font-weight-medium text-success">' + mods[i].value + '</span>';
                    else if (mods[i].attribute === 'status')
                        result += 'status kepegawaian <span class="font-weight-medium text-success text-uppercase">' + approval.officer.status + '</span> menjadi <span class="font-weight-medium text-success text-uppercase">' + mods[i].value + '</span';
                    else if (mods[i].attribute === 'identity')
                        result += 'NIP <span class="font-weight-medium text-success">' + approval.officer.identity + '</span> menjadi <span class="font-weight-medium text-success">' + mods[i].value + '</span>';
                }
                result += '.';
                result = '<p class="text-wrap" style="max-width: 40vw">' + result + '</p>';

                return [result, id_user];
            } else if (approval.type === 'del') {
                const result = '<p class="text-wrap" style="max-width: 40vw">Permintaan penghapusan data pegawai honorer dengan nama <span class="text-success font-weight-medium">' + approval.officer.user.name + '</span> dan masa kerja <span class="text-success font-weight-medium">' + _date.timestamp_old(approval.officer.created_at) + '</p>';
                id_user = parseInt(approval.modifies[0].value);

                return [result, id_user];
            } else {
                const result = '<p class="text-wrap" style="max-width: 40vw">Permintaan perubahan hak akses kelola sistem milik <span class="text-success font-weight-medium">' + approval.officer.user.name + '</span>';
                id_user = parseInt(approval.modifies[0].value);

                return [result, id_user];
            }
        }
        _card.render({
            element : 'card-approvals',
            items   : [
                {
                    title : 'Daftar Permintaan Persetujuan',
                    label : 'ti-user',
                    id : 'data-approvals',
                    content : _tables.render({
                        element : 'approvals-table',
                        template : 'custom',
                        column : [
                            {content : 'Permintaan'},
                            {content : 'Oleh'},
                            {content : 'Pada'},
                            {content : 'Aksi'},
                        ]
                    }),
                }
            ],
        });
        for (let i = 0; i < data.length; i++) {
            const approval = data[i];
            const result   = approvalTitle(approval);
            let   date_old = _date.timestamp_old(approval.created_at);
            if (date_old === 'sehari')
                date_old   = 'hari ini';
            else
                date_old  += ' yang lalu';
            _tables.insert({
                element : 'approvals-table',
                column  : [
                    {content : result[0]},
                    {content : '<span class="font-weight-medium">' + approval.officer.identity + '</span> <small class="text-muted">' + approval.from + '</small>'},
                    {content : '<span class="font-weight-medium">' + date_old + '</span>'},
                    {
                        content : _btn_group.make([
                            _btn.render({
                                size : 'sm',
                                operate : 'setuju',
                                type : 'success',
                                title : 'setuju',
                                content : '<i class="ti-check font-weight-bold"></i>',
                                fun : function (e) {
                                    _transition.in();
                                    _response.post({async:false, url:'{{url('approvalVerify')}}', data:{id:approval.id}, file:new FormData()});
                                    const res = _response.response;
                                    let output;
                                    if (res._status) {
                                        if (res.status === 'success') {
                                            let tar = e.target;
                                            while (tar.nodeName !== 'TR') {
                                                tar = tar.parentNode;
                                            }
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
                                    _transition.out();
                                    _popup.content(output);
                                }
                            }),
                            _delete.render(function (e) {
                                _transition.in();
                                _response.post({async:false, url:'{{url('approvalDelete')}}', data:{id:approval.id}, file:new FormData()});
                                const res = _response.response;
                                let output;
                                if (res._status) {
                                    if (res.status === 'success') {
                                        let tar = e.target;
                                        while (tar.nodeName !== 'TR') {
                                            tar = tar.parentNode;
                                        }
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
                                _transition.out();
                                _popup.content(output);
                            })
                        ]),
                    },
                ],
            });
        }
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
