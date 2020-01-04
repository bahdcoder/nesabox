@component('mail::message')
# Hello,

You've been invited to join team {{ $invite->team->name }} on {{ config('app.name') }}

@component('mail::button', ['url' => $url])
View your teams
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
