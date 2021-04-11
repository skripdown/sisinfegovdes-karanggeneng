@if (\App\Http\back\_Authorize::manage(\App\Http\back\authorize\Account::class))
    <script>const _accounts=_data.account;_accounts.refresh(function(){_response.get('{{url('/roles'.\App\Http\back\_UI::$FLAG_HIDE)}}',false);return _response.response;});</script>
@endif
