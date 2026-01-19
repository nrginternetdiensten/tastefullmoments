<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="building-office-2" :href="route('accounts.index')" :current="request()->routeIs('accounts.*')" wire:navigate>
                        {{ __('Accounts') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="folder" :href="route('email-folders.index')" :current="request()->routeIs('email-folders.*')" wire:navigate>
                        {{ __('Email Folders') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="envelope" :href="route('email-items.index')" :current="request()->routeIs('email-items.*')" wire:navigate>
                        {{ __('Email Items') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="calculator" :href="route('invoice-taxes.index')" :current="request()->routeIs('invoice-taxes.*')" wire:navigate>
                        {{ __('Invoice Taxes') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="tag" :href="route('ticket-statuses.index')" :current="request()->routeIs('ticket-statuses.*')" wire:navigate>
                        {{ __('Ticket Statuses') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('ticket-channels.index')" :current="request()->routeIs('ticket-channels.*')" wire:navigate>
                        {{ __('Ticket Channels') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="rectangle-stack" :href="route('ticket-types.index')" :current="request()->routeIs('ticket-types.*')" wire:navigate>
                        {{ __('Ticket Types') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="swatch" :href="route('color-schemes.index')" :current="request()->routeIs('color-schemes.*')" wire:navigate>
                        {{ __('Color Schemes') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Support')" class="grid">
                    <flux:sidebar.item icon="ticket" :href="route('tickets.index')" :current="request()->routeIs('tickets.*')" wire:navigate>
                        {{ __('Tickets') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Financieel')" class="grid">
                    <flux:sidebar.item icon="document-text" :href="route('invoices.index')" :current="request()->routeIs('invoices.*')" wire:navigate>
                        {{ __('Facturen') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="tag" :href="route('invoice-statuses.index')" :current="request()->routeIs('invoice-statuses.*')" wire:navigate>
                        {{ __('Factuur Statussen') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Access Control')" class="grid">
                    <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>
                        {{ __('Users') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.*')" wire:navigate>
                        {{ __('Roles') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="key" :href="route('permissions.index')" :current="request()->routeIs('permissions.*')" wire:navigate>
                        {{ __('Permissions') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
