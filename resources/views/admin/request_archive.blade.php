@extends('admin.template')

@section('title')
    Permohonan Arsip
@endsection

@section('page-breadcrumb')
    Kelola Permohonan Surat
@endsection

@section('sub-breadcrumb')
    Halaman Manajemen Surat Daring {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-request"></div>
@endsection

@section('popup')
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _request=_data.request;
        _request.refresh(function () {
            _response.get('{{url('/requestarchives'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        let content;
        if (_request._len === 0) {
            content = document.createElement('div');
            content.setAttribute('class', 'text-center text-muted');
            content.innerHTML = '<div class="m-4 p-4">tidak ada permohonan surat atau arsip.</div>';
            content = [{
                title   : 'Data Permohonan',
                label   : 'ti-archive',
                id      : 'data-permohonan',
                content : content
            }];
        }
        else {
            content = [
                {
                    title : 'Data Permohonan',
                    label : 'ti-archive',
                    id : 'data-permohonan',
                    content : _tables.render({
                        element : 'request-data',
                        template : 'custom',
                        column : [
                            {content : 'Profil Pemohon'},
                            {content : 'Jenis Permohonan'},
                            {content : 'Tanggal'},
                        ]
                    })
                },
                {
                    title : 'Detail Permohonan',
                    label : 'ti-briefcase',
                    content : _ui_factory.__general.compact_els('div', [
                        _formfield.render({
                            element : 'form-request',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'id', type:'hidden'},
                                {name:'nama', type: 'text', placeholder: 'nama pemohon', readOnly: true, value:'nama pemohon'},
                                {name:'jenis', type: 'text', placeholder: 'jenis permohonan', readOnly: true, value:'jenis permohonan'},
                                {name:'token', type:'text', placeholder: 'akses token', value: _rand.pass(22),readOnly: true,header: 'akses token dokumen'},
                                {name:'berkas',type : 'file',placeholder:'berkas',header:'unggah berkas permohonan dalam bentuk <code>.docx</code> atau <code>.pdf</code>'},
                                {type:'submit',placeholder: 'simpan'}
                            ]
                        })[0],
                        _formfield.render({
                            element : 'form-delete',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'id', type:'hidden'},
                                {type:'delete',placeholder: 'hapus'}
                            ]
                        })[0],
                    ])
                }
            ]
        }
        _card.render({
            element : 'data-request',
            items : content
        });
        _transition.out();
    </script>
@endsection
