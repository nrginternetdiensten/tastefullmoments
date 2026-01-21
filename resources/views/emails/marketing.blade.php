<x-mail::message>
# {{ $title ?? 'Nieuwsbrief' }}

{{ $greeting ?? 'Hallo!' }}

@if(isset($content))
{!! $content !!}
@else
Bedankt voor je interesse in {{ config('app.name') }}.
@endif

@if(isset($actionText) && isset($actionUrl))
<x-mail::button :url="$actionUrl">
{{ $actionText }}
</x-mail::button>
@endif

@if(isset($sections) && is_array($sections))
@foreach($sections as $section)
<x-mail::panel>
## {{ $section['title'] ?? '' }}

{{ $section['content'] ?? '' }}
</x-mail::panel>
@endforeach
@endif

@if(isset($footer))
{{ $footer }}
@endif

Met vriendelijke groet,<br>
{{ config('app.name') }}

@if(isset($unsubscribeUrl))
---

<small>[Afmelden voor deze nieuwsbrief]({{ $unsubscribeUrl }})</small>
@endif
</x-mail::message>
