@extends('admin.template')

@section('title')
    Arsip
@endsection

@section('page-breadcrumb')
    Kelola Arsip Surat
@endsection

@section('sub-breadcrumb')
    Halaman Kelola Arsip Surat {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="archive-data"></div>
@endsection

@section('popup')
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _archive=_data.archive;
        _album.refresh(function () {
            _response.get('{{url('/archives'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        _card.render({
            element : 'archive-data',
            items : [
                {
                    title : 'Data Arsip',
                    label : 'ti-archive',
                    id : 'data-archive',
                    content : _tables.render({
                        element : 'archive-data',
                        column : [
                            {content : 'Nama Dokumen'},
                            {content : 'Jenis'},
                            {content : 'Permohonan Dari'},
                        ]
                    })
                }
            ]
        });
        _transition.out();
    </script>
@endsection
