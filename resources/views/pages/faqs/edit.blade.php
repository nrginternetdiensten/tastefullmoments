<x-layouts.app>
    <x-slot name="title">{{ __('FAQ bewerken') }}</x-slot>

    <livewire:faqs.form :faq="$faq" />
</x-layouts.app>
