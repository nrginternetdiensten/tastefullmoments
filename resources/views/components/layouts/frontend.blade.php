<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 antialiased" x-data="{ mobileMenuOpen: false }">
    <!-- Top Navigation -->
    <nav class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 group">
                        <div class="flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-lg shadow-md group-hover:shadow-lg transition-shadow">
                            {{ substr(config('app.name'), 0, 1) }}
                        </div>
                        <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Desktop Navigation - Always visible -->
                <div class="flex items-center space-x-1 ml-8">
                    <a href="{{ route('home') }}" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                        Home
                    </a>
                    <a href="{{ route('frontend.about') }}" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('frontend.about') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                        Over Ons
                    </a>
                    <a href="{{ route('frontend.faq') }}" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('frontend.faq') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                        Veelgestelde Vragen
                    </a>
                    <a href="{{ route('frontend.contact') }}" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('frontend.contact') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                        Contact
                    </a>
                </div>

                <!-- Desktop Auth - Always visible -->
                <div class="flex items-center space-x-3 ml-auto">
                    @auth
                        <a href="{{ route('account.dashboard') }}" wire:navigate class="px-3 py-2 text-sm font-medium text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400">
                            Mijn Account
                        </a>
                        <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-sm font-medium text-white hover:bg-blue-700">
                            CMS Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="px-3 py-2 text-sm font-medium text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400">
                            Inloggen
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate class="px-4 py-2 rounded-md bg-blue-600 text-sm font-medium text-white hover:bg-blue-700">
                                Registreren
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <svg x-show="!mobileMenuOpen" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden pb-3 pt-2 space-y-1">
                <a href="{{ route('home') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                    Home
                </a>
                <a href="{{ route('frontend.about') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('frontend.about') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                    Over Ons
                </a>
                <a href="{{ route('frontend.faq') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('frontend.faq') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                    Veelgestelde Vragen
                </a>
                <a href="{{ route('frontend.contact') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('frontend.contact') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                    Contact
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Dashboard
                    </a>
                @else
                    <div class="pt-2 border-t border-zinc-200 dark:border-zinc-700 space-y-1">
                        <a href="{{ route('login') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Inloggen
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Registreren
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen pb-3">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 mt-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
                <!-- Company Info -->
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xl shadow-lg">
                            {{ substr(config('app.name'), 0, 1) }}
                        </div>
                        <span class="text-xl font-bold">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                        {{ __('Uw partner in digitale oplossingen. Wij helpen u bij het realiseren van uw online ambities.') }}
                    </p>
                    <!-- Social Media -->
                    <div class="flex gap-3">
                        <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-zinc-200 hover:bg-blue-600 dark:bg-zinc-800 dark:hover:bg-blue-600 text-zinc-700 hover:text-white dark:text-zinc-300 dark:hover:text-white transition-all">
                            <flux:icon.globe-alt class="size-5" />
                        </a>
                        <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-zinc-200 hover:bg-blue-600 dark:bg-zinc-800 dark:hover:bg-blue-600 text-zinc-700 hover:text-white dark:text-zinc-300 dark:hover:text-white transition-all">
                            <flux:icon.envelope class="size-5" />
                        </a>
                        <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-zinc-200 hover:bg-blue-600 dark:bg-zinc-800 dark:hover:bg-blue-600 text-zinc-700 hover:text-white dark:text-zinc-300 dark:hover:text-white transition-all">
                            <flux:icon.phone class="size-5" />
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="mb-6 text-base font-bold text-zinc-900 dark:text-zinc-100">
                        {{ __('Snelle Links') }}
                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <flux:icon.chevron-right class="size-4 group-hover:translate-x-1 transition-transform" />
                                {{ __('Home') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.about') }}" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <flux:icon.chevron-right class="size-4 group-hover:translate-x-1 transition-transform" />
                                {{ __('Over Ons') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.contact') }}" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <flux:icon.chevron-right class="size-4 group-hover:translate-x-1 transition-transform" />
                                {{ __('Contact') }}
                            </a>
                        </li>
                        @auth
                        <li>
                            <a href="{{ route('frontend.faq') }}" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <flux:icon.chevron-right class="size-4 group-hover:translate-x-1 transition-transform" />
                                {{ __('Veelgestelde vragen') }}
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="mb-6 text-base font-bold text-zinc-900 dark:text-zinc-100">
                        {{ __('Juridisch') }}
                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('frontend.content','privacybeleid')}}" class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <flux:icon.chevron-right class="size-4 group-hover:translate-x-1 transition-transform" />
                                {{ __('Privacybeleid') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.content','algemene-voorwaarden')}}" class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <flux:icon.chevron-right class="size-4 group-hover:translate-x-1 transition-transform" />
                                {{ __('Algemene Voorwaarden') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.content','cookiebeleid')}}" class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <flux:icon.chevron-right class="size-4 group-hover:translate-x-1 transition-transform" />
                                {{ __('Cookiebeleid') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="mb-6 text-base font-bold text-zinc-900 dark:text-zinc-100">
                        {{ __('Contact Informatie') }}
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <flux:icon.envelope class="size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" />
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-500">{{ __('Email') }}</p>
                                <a href="mailto:{{setting(7)}}" class="text-base text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400 transition-colors">
                                    {{setting(7)}}
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <flux:icon.phone class="size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" />
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-500">{{ __('Telefoon') }}</p>
                                <a href="tel:{{setting(6)}}" class="text-base text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400 transition-colors">
                                    {{setting(6)}}
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <flux:icon.map-pin class="size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" />
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-500">{{ __('Adres') }}</p>
                                <p class="text-base text-zinc-700 dark:text-zinc-300">
                                    {{setting(2)}} {{setting(3)}}<br>
                                    {{setting(4)}} {{setting(5)}}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-12 border-t border-zinc-200 dark:border-zinc-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 text-center md:text-left">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('Alle rechten voorbehouden.') }}
                    </p>
                    <div class="flex items-center gap-6 text-sm text-zinc-600 dark:text-zinc-400">
                        <span>{{ __('Gemaakt met') }} ❤️ {{ __('in Nederland') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @fluxScripts
</body>
</html>
