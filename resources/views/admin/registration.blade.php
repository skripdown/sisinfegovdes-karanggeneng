@extends('admin.template')

@section('title')
    Registrasi Penduduk
@endsection

@section('page-breadcrumb')
    Data Registrasi Penduduk
@endsection

@section('sub-breadcrumb')
    Halaman Info Data Registrasi Penduduk {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-registrasi"></div>
@endsection

@section('popup')
    <div id="popup-notification"></div>
    <div id="popup-img"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _registrations = _data.citizen;
        _registrations.refresh(function () {
            _response.get('{{url('/registrations'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        let cardContent;
        if (_registrations._len === 0) {
            cardContent = document.createElement('div');
            cardContent.innerHTML = '<div class="text-muted text-center pt-4 pb-4">tidak ada permintaan registrasi</div>';
        }
        else {
            cardContent = _tables.render({
                element : 'table-registrations',
                template : 'custom',
                column : [
                    {content : 'Profil'},
                    {content : 'Nomor Induk'},
                    {content : 'Email'},
                    {content : 'Tempat Tanggal Lahir'},
                    {content : 'KTP'},
                    {content : 'Aksi'},
                ]
            })
        }
        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'data-registrasi',
            items : [
                {
                    title : 'Data Registrasi Penduduk',
                    label : 'ti-user',
                    id : 'data-reg-penduduk',
                    content : cardContent
                }
            ]
        });
        if (_registrations._len > 0) {
            let nodeIter = _registrations._first;
            while (nodeIter !== undefined) {
                const node = nodeIter;
                let operate_param = {};
                if (node.verified)
                    operate_param = {type:'secondary',title:'batal verifikasi', operate:'batal', content:'batal'};
                else
                    operate_param = {type:'success',title:'verifikasi', operate:'verifikasi', content:'verifikasi'};
                _tables.insert({
                    element : 'table-registrations',
                    column  : [
                        {content : '<div class="d-flex no-block align-items-center"><div class="mr-3"><img src="{{asset(env('PATH_CITIZEN_PROFILE'))}}/'+node.pic+'" alt="user" class="rounded-circle" width="45" height="45" /></div><div class=""><h5 class="text-dark mb-0 font-16 font-weight-medium">'+node.name+'</h5><span class="text-muted font-14">'+node.gender+'</span></div></div>',},
                        {content : node.nid},
                        {content : node.email},
                        {content : node.place_birth + ', ' + _date.date(node.day_birth, node.month_birth, node.year_birth)},
                        {
                            content : node.id_pic,
                            click   : function () {
                                _popup.content({
                                    id : 'popup-img',
                                    header : '<strong>gambar KTP</strong>',
                                    close : true,
                                    content : '<div style=""><img src="{{asset(env('PATH_CITIZEN_ID_CARD'))}}/'+node.id_pic+'" alt="" style="display:block;max-width:100%"></div>'
                                });
                            }
                        },
                        {content : _btn_group.make(
                            [
                                _delete.render(function (e) {
                                    const event = e;
                                    _popup.content({
                                        id : 'popup-notification',
                                        header : '<strong>hapus permintaan registrasi</strong>',
                                        content : '<p>apakah anda yakin ingin menghapus permintaan registrasi oleh <code class="font-weight-bold">'+node.name+'</code> dengan NIK <code class="font-weight-bold">'+node.nid+'</code>?</p>',
                                        footer : _btn_group.make([
                                            _btn.render({
                                                operate : 'batal',
                                                type : 'success',
                                                title : 'batal',
                                                content : 'batal',
                                                fun : function () {
                                                    _popup.close('popup-notification');
                                                }
                                            }),
                                            _btn.render({
                                                operate : 'hapus',
                                                type : 'secondary',
                                                title : 'hapus',
                                                content : 'hapus',
                                                fun : function () {
                                                    _popup.close('popup-notification');
                                                    _transition.in();
                                                    _response.post({async:false, url:'{{url('registrationDelete')}}', data:{nid:node.nid}, file: new FormData()});
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
                                        ])
                                    });
                                }),
                                _btn.render({
                                    type  : operate_param.type,
                                    title : operate_param.title,
                                    operate : operate_param.operate,
                                    content : operate_param.content,
                                    fun : function (e) {
                                        _transition.in();
                                        const operate = e.target.getAttribute('data-operate');
                                        let action;
                                        if (operate === 'verifikasi')
                                            action = false;
                                        else
                                            action = true;
                                        _response.post({async:false, url:'{{url('registrationVerification')}}', data:{action:action}, file:new FormData()});
                                        if (_response.response._status) {
                                            const clicked = e.target;
                                            if (operate === 'verifikasi') {
                                                clicked.setAttribute('class', 'btn btn-sm rounded-0 btn-secondary');
                                                clicked.setAttribute('title', 'batal');
                                                clicked.setAttribute('data-operate', 'batal');
                                                clicked.innerHTML = 'batal';
                                            }
                                            else {
                                                clicked.setAttribute('class', 'btn btn-sm rounded-0 btn-success');
                                                clicked.setAttribute('title', 'verifikasi');
                                                clicked.setAttribute('data-operate', 'verifikasi');
                                                clicked.innerHTML = 'verifikasi';
                                            }
                                        }
                                        _transition.out();
                                    }
                                })
                            ])
                        },
                    ],
                });
                nodeIter = nodeIter._next;
            }
            _popup.init({element : 'popup-notification', align : 'center'});
            _popup.init({element : 'popup-img', align : 'center'});
        }
    </script>
@endsection
