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
                    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2">
                        E-mailadres Wijzigen
                    </h2>
                    <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                        Wijzig het e-mailadres dat is gekoppeld aan uw account. U ontvangt een verificatie e-mail op het nieuwe adres.
                    </p>

                    <!-- Current Email Status -->
                    <div class="mb-6 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                @if (Auth::user()->email_verified_at)
                                    <div class="flex size-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                        <svg class="size-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="flex size-10 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
                                        <svg class="size-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ Auth::user()->email }}</p>
                                @if (Auth::user()->email_verified_at)
                                    <p class="text-sm text-green-600 dark:text-green-400">Geverifieerd op {{ Auth::user()->email_verified_at->format('d-m-Y') }}</p>
                                @else
                                    <p class="text-sm text-orange-600 dark:text-orange-400">Nog niet geverifieerd</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('account.email.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Nieuw E-mailadres
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                Na het wijzigen van uw e-mailadres moet u het nieuwe adres verifiëren.
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <a href="{{ route('account.dashboard') }}" wire:navigate class="px-6 py-2 text-sm font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100">
                                Annuleren
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                E-mailadres Wijzigen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.frontend>
