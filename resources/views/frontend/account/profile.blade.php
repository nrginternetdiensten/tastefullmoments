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
                        Profiel Bewerken
                    </h2>

                    <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Voornaam *
                                </label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" required class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Achternaam *
                                </label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" required class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="telephone_number" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Telefoonnummer
                            </label>
                            <input type="tel" id="telephone_number" name="telephone_number" value="{{ old('telephone_number', Auth::user()->telephone_number) }}" class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+31 6 12345678">
                            @error('telephone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                E-mailadres
                            </label>
                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" disabled class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-zinc-100 dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400">
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                Ga naar <a href="{{ route('account.email') }}" wire:navigate class="text-blue-600 hover:underline">E-mailadres</a> om uw e-mailadres te wijzigen.
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <a href="{{ route('account.dashboard') }}" wire:navigate class="px-6 py-2 text-sm font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100">
                                Annuleren
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.frontend>
