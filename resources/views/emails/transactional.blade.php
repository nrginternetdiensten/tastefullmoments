<x-mail::message>
# {{ $title ?? 'Transactionele E-mail' }}

{{ $greeting ?? 'Beste,' }}

@if(isset($content))
{!! $content !!}
@else
Dit is een transactionele e-mail van {{ config('app.name') }}.
@endif

@if(isset($actionText) && isset($actionUrl))
<x-mail::button :url="$actionUrl">
{{ $actionText }}
</x-mail::button>
@endif

@if(isset($footer))
{{ $footer }}
@endif

Met vriendelijke groet,<br>
{{ config('app.name') }}

@if(isset($disclaimer))
<x-mail::panel>
{{ $disclaimer }}
</x-mail::panel>
@endif
</x-mail::message>
