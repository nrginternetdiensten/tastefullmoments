<x-layouts.frontend>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mb-8">
            Mijn Account
        </h1>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <x-account-sidebar />
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 border border-zinc-200 dark:border-zinc-700">
                    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-6">
                        Welkom, {{ Auth::user()->name }}
                    </h2>

                    <div class="space-y-6">
                        <!-- Quick Stats -->
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Account Status</p>
                                @if (Auth::user()->email_verified_at)
                                    <p class="text-lg font-semibold text-green-600">Geverifieerd</p>
                                @else
                                    <p class="text-lg font-semibold text-orange-600">Niet Geverifieerd</p>
                                @endif
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Lid sinds</p>
                                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ Auth::user()->created_at->format('d-m-Y') }}</p>
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">E-mailadres</p>
                                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <!-- Account Balances -->
                        @if (Auth::user()->accounts->count() > 0)
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                                    {{ Auth::user()->accounts->count() === 1 ? 'Account Saldo' : 'Account Saldo\'s' }}
                                </h3>
                                <div class="space-y-3">
                                    @foreach (Auth::user()->accounts as $account)
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-zinc-900 dark:to-zinc-800 rounded-lg border border-blue-200 dark:border-zinc-700">
                                            <div class="flex items-center gap-3">
                                                <div class="flex size-10 items-center justify-center rounded-lg bg-blue-600 text-white">
                                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                    </svg>
                                                </div>
                                                @if (Auth::user()->accounts->count() > 1)
                                                    <div>
                                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $account->name }}</p>
                                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Account #{{ $account->id }}</p>
                                                    </div>
                                                @else
                                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">Huidig Saldo</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold {{ $account->balance_cents >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $account->balance_cents >= 0 ? '' : '-' }}€{{ number_format(abs($account->balance_cents) / 100, 2, ',', '.') }}
                                                </p>
                                                @if ($account->credit_limit_cents)
                                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                        Credit limiet: €{{ number_format($account->credit_limit_cents / 100, 2, ',', '.') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quick Actions -->
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Snelle Acties</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <a href="{{ route('account.profile') }}" wire:navigate class="flex items-center gap-4 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                    <div class="flex size-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">Profiel Bewerken</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Wijzig uw persoonlijke gegevens</p>
                                    </div>
                                </a>
                                <a href="{{ route('account.password') }}" wire:navigate class="flex items-center gap-4 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                    <div class="flex size-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">Wachtwoord Wijzigen</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Beveilig uw account</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.frontend>
