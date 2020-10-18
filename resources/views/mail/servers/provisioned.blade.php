@component('mail::message')
Your server {{ $server->ip_address }} has been provisioned.
<br>
<br>
Sudo password for nesa user: <b>{{ $server->sudo_password }}</b>
<br>
<br>

@if($server->mysql_root_password)
Root password for Mysql v5.7: <b>{{ $server->mysql_root_password }}</b>
<br>
<br>
@endif

@if($server->mysql8_root_password)
Root password for Mysql v8: <b>{{ $server->mysql8_root_password }}</b>
<br>
<br>
@endif

@if($server->mariadb_root_password)
Root password for MariaDB v10.3: <b>{{ $server->mariadb_root_password }}</b>
<br>
<br>
@endif

@if($server->mongodb_admin_password)
Admin password for MongoDB v4.2: <b>{{ $server->mongodb_admin_password }}</b>
<br>
<br>
@endif

@if($server->postgres_root_password)
Root password for Postgresql v11: <b>{{ $server->postgres_root_password }}</b>
<br>
<br>
@endif


@component('mail::button', ['url' => $url])
Manage server
@endcomponent

Thanks,<br>
<br>

Frantz from {{ config('app.name') }}
@endcomponent
