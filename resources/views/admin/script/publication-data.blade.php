@if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Publication::class))
    <script>const _albums=_data.album,_photos=_data.photo,_blogs=_data.blog;_albums.refresh(function(){_response.get('{{url('/albums'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});_photos.refresh(function(){_response.get('{{url('/photos'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});_blogs.refresh(function(){_response.get('{{url('/blogs'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});</script>
@endif
