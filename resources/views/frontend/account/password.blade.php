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
                        Wachtwoord Wijzigen
                    </h2>
                    <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                        Zorg ervoor dat uw account een lang, willekeurig wachtwoord gebruikt om veilig te blijven.
                    </p>

                    <form method="POST" action="{{ route('account.password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Huidig Wachtwoord
                            </label>
                            <input type="password" id="current_password" name="current_password" required class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Nieuw Wachtwoord
                            </label>
                            <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Bevestig Wachtwoord
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <a href="{{ route('account.dashboard') }}" wire:navigate class="px-6 py-2 text-sm font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100">
                                Annuleren
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                Wachtwoord Wijzigen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.frontend>
