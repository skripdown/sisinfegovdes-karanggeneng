@extends('admin.template')

@section('title')
    Album
@endsection

@section('page-breadcrumb')
    Kelola Album
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Data Album {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="album"></div>
@endsection

@section('popup')
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _album=_data.album;
        _album.refresh(function () {
            _response.get('{{url('/albums'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(photos)')}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        _card.render({
            element : 'album',
            items : [
                {
                    title : 'Album Publikasi',
                    label : 'ti-layout-list-large-image',
                    id : 'data-album',
                    content : _tables.render({
                        element : 'album-data',
                        column : [
                            {content : 'Judul'},
                            {content : 'Jumlah Foto'},
                            {content : 'Terakhir Diubah'},
                        ],
                    })
                },
                {
                    title : 'Album Baru',
                    label : 'ti-plus',
                    id : 'album-new',
                    content : _ui_factory.__general.compact_els('div',[
                        _formfield.render({
                            element : 'form-add-title',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'name',type : 'text',placeholder:'judul album'},
                                {type:'submit', placeholder: 'simpan'},
                            ],
                        })[0],
                        _tables.render({
                            element : 'album-new-data',
                            template : 'custom',
                            column : [
                                {content : 'Foto'},
                            ],
                        }),
                        _formfield.render({
                            element : 'form-add',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'profile_pic',type : 'file',placeholder:'tambah foto',header:'berkas dalam bentuk <code>.jpg</code> , <code>.jpeg</code> atau <code>.png</code>'},
                                {type:'submit', placeholder: 'tambah'},
                            ],
                        })[0],
                    ])
                },
                {
                    title : 'Detail Album',
                    label : 'ti-image',
                    id : 'detail-album',
                    content : _ui_factory.__general.compact_els('div',[
                        _formfield.render({
                            element : 'form-edit-title',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'name',type : 'text',placeholder:'judul album'},
                                {type:'submit', placeholder: 'ganti'},
                            ],
                        })[0],
                        _formfield.render({
                            element : 'form-edit',
                            width : ['sm-12','md-9','lg-6','xl-5'],
                            fields : [
                                {name:'profile_pic',type : 'file',placeholder:'tambah foto',header:'berkas dalam bentuk <code>.jpg</code> , <code>.jpeg</code> atau <code>.png</code>'},
                                {type:'submit', placeholder: 'tambah'},
                            ],
                        })[0],
                        _tables.render({
                            element : 'album-new-data',
                            template : 'custom',
                            column : [
                                {content : 'Foto'},
                                {content : 'Edit'},
                            ],
                        }),
                    ])
                }
            ]
        });
    </script>
@endsection
