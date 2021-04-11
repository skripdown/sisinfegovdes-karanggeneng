@extends('admin.template')

@section('title')
    Keluarga
@endsection

@section('page-breadcrumb')
    Data Keluarga
@endsection

@section('sub-breadcrumb')
    Halaman Informasi Data Keluarga {{env('APP_NAME')}}
@endsection

@section('content')
    <div id="data-keluarga"></div>
@endsection

@section('popup')
    <div id="popover-more"></div>
    <div id="popup-notification"></div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const pic_admin = '{{asset(env('PATH_ADMIN_PROFILE'))}}/';
        const pic_user  = '{{asset(env('PATH_CITIZEN_PROFILE'))}}/';
        const _families = _data.family;
        _families.refresh(function () {
            _response.get('{{url('/families'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(district,hamlet,neighboor,citizens,citizens.user,citizens.user.officer,citizens.citoccupation.occupation)')}}',false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function setProfessions(citizens) {
            const professions = {};
            const keys        = [];
            for (let i = 0; i < citizens.length; i++) {
                const citizen = citizens[i];
                const name    = citizen.citoccupation.occupation.name;
                if (professions[name] === undefined) {
                    professions[name] = 1
                    keys.push(name);
                }
            }

            return keys;
        }
        _popover.init('popover-more','{{asset('')}}/');
        const concat = _ui_factory.__general.compact_els;
        _card.render({
            element : 'data-keluarga',
            items : [
                {
                    title : 'Data Keluarga',
                    label : 'ti-layout-grid3',
                    id : 'data-keluarga',
                    content : _tables.render({
                        element : 'table-family',
                        template : 'custom',
                        column : [
                            {content : 'Nomor Kartu Keluarga'},
                            {content : 'Dusun'},
                            {content : 'RT'},
                            {content : 'RW'},
                            {content : 'Anggota Keluarga'},
                            {content : 'Profesi Keluarga'},
                        ]
                    })
                }
            ],
        });
        let node_iter = _families._first;
        while (node_iter !== undefined) {
            const family = node_iter;
            _tables.insert({
                element : 'table-family',
                column  : [
                    {content : '<span class="font-weight-medium">' + family.number + '</span>',},
                    {content : '<span class="font-weight-medium">' + family.district.name + '</span>',},
                    {content : '<span class="font-weight-medium">' + family.hamlet.name + '</span>',},
                    {content : '<span class="font-weight-medium">' + family.neighboor.name + '</span>',},
                    {
                        content : _popover.render({
                            opt_class :'',
                            entity    :'',
                            items     :family.citizens,
                            path_1    : pic_user,
                            path_2    : pic_admin,
                        }),
                    },
                    {
                        content : _taglist.render({
                            element : 'profesi-' + family.number,
                            keys    : setProfessions(family.citizens),
                            opt_class : 'pl-2 pr-2 text-capitalize',
                        }),
                    },
                ]
            });
            node_iter    = node_iter._next;
        }
        _popup.init({element : 'popup-notification', align : 'center',});
    </script>
@endsection
