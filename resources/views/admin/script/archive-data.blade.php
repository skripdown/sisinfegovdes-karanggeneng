@if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Archive::class))
    <script>const _archives=_data.archive,_requests=_data.request;_archives.refresh(function(){_response.get('{{url('/archives'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});_requests.refresh(function(){_response.get('{{url('/requestarchives'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});</script>
@endif
