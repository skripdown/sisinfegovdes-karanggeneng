@if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Employee::class))
    <script>
        const _officers=_data.officer,_mutIns=_data.mutation_in,_mutOuts=_data.mutation_out;
        _officers.refresh(function(){_response.get('{{url('/officers'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});
        _mutIns.refresh(function(){_response.get('{{url('/mutins'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});
        _mutOuts.refresh(function(){_response.get('{{url('/mutouts'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});
    </script>
@endif
