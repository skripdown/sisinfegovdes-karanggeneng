@extends('admin.template')

@section('title')
    Dashboard
@endsection

@section('page-breadcrumb')
    Kelola Agenda Berita
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Publikasi Konten Agenda dan Berita {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="main-content"></div>
@endsection

@section('popup')
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _url   = '{{route('ckeditor.image-upload', ['_token'=>csrf_token()])}}';
        const _blogs = _data.blog;
        _blogs.refresh(function () {
            _response.get('{{url('/blogs'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script src="{{asset(env('LIB_PATH').'core/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset(env('LIB_PATH').'core/ckeditor/adapters/jquery.js')}}"></script>
    <script>
        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'main-content',
            items : [
                {
                    title : 'Data Agenda dan Berita',
                    label : 'ti-agenda',
                    id : 'data-agenda-berita',
                    content : _tables.render({
                        element : 'table-blog',
                        template : 'custom',
                        column : [
                            {content : 'Judul'},
                            {content : 'Penulis'},
                            {content : 'Tanggal'},
                        ]
                    })
                },
                {
                    title : 'Artikel Baru',
                    label : 'ti-plus',
                    id : 'form-artikel-baru',
                    content : _formfield.render({
                        element : 'form-add',
                        width : ['sm-12','md-12','lg-12','xl-12'],
                        fields : [
                            {},
                            {name: 'judul', type:'text', placeholder:'judul', header: 'judul berita. berkas dalam bentuk <code>.jpg</code> , <code>.jpeg</code> atau <code>.png</code>'},
                            {name: 'thumbnail', type:'file', placeholder:'thumbnail', header: 'thumbnail berita'},
                            {},
                            {name: 'editor', type:'editor', url:_url},
                            {type:'submit', placeholder:'publish'},
                        ]
                    })[0]
                },
                {
                    title : 'Ubah Artikel',
                    label : 'ti-pencil',
                    id : 'form-artikel-ubah',
                    content : concat('div',
                        [
                            _formfield.render({
                                element : 'form-edit',
                                width : ['sm-12','md-12','lg-12','xl-12'],
                                fields : [
                                    {},
                                    {name: 'judul', type:'text', placeholder:'judul', header: 'judul berita. berkas dalam bentuk <code>.jpg</code> , <code>.jpeg</code> atau <code>.png</code>'},
                                    {name: 'thumbnail', type:'file', placeholder:'thumbnail', header: 'thumbnail berita'},
                                    {},
                                    {name: 'editorEdit', type:'editor', url:_url},
                                    {type:'submit', placeholder:'ubah'},
                                ]
                            })[0],
                            _formfield.render({
                                element:'form-delete',
                                width : ['sm-12','md-12','lg-12'],
                                fields : [
                                    {name:'id',type : 'hidden',value:''},
                                    {name:'name',type : 'hidden',value:'undefined'},
                                    {name:'verify',type : 'text',placeholder:'konfirmasi hapus',header:'klik tombol <span class="bg-danger text-white pl-2 pr-2 rounded">hapus</span> untuk menghapus artikel. masukkan judul blog sebagai konfirmasi hapus.'},
                                    {type:'delete',},
                                ],
                            })[0],
                        ]
                    )
                },
            ]
        });
        _formfield.sync(_url);
    </script>
@endsection
