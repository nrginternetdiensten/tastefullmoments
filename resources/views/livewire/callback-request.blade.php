<?php

use App\Models\LeadItem;
use function Livewire\Volt\{state, rules};

state([
    'companyname' => '',
    'first_name' => '',
    'last_name' => '',
    'phonenumber' => '',
]);

rules([
    'companyname' => 'required|string|max:255',
    'first_name' => 'required|string|max:255',
    'last_name' => 'required|string|max:255',
    'phonenumber' => 'required|string|max:255',
]);

$submit = function () {
    $this->validate();

    LeadItem::create([
        'lead_channel_id' => 1,
        'lead_category_id' => 1,
        'companyname' => $this->companyname,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'phonenumber' => $this->phonenumber,
        'internal_note' => 'Terugbelverzoek via homepage',
        'ipaddress' => request()->ip(),
    ]);

    session()->flash('callback-success', 'Bedankt! We nemen zo snel mogelijk contact met u op.');

    $this->reset();
};

?>

<div class="bg-white dark:bg-zinc-800 rounded-2xl p-8 md:p-12 border border-zinc-200 dark:border-zinc-700 shadow-lg">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center size-16 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mb-4">
                <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-3">Wilt u teruggebeld worden?</h2>
            <p class="text-zinc-600 dark:text-zinc-400 text-lg">Laat uw gegevens achter en we bellen u zo snel mogelijk terug.</p>
        </div>

        @if (session('callback-success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg">
                <div class="flex items-center gap-3">
                    <svg class="size-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('callback-success') }}</span>
                </div>
            </div>
        @endif

        <form wire:submit="submit" class="space-y-5">
            <div>
                <label for="companyname" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Bedrijfsnaam *</label>
                <input
                    type="text"
                    id="companyname"
                    wire:model="companyname"
                    class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Uw bedrijfsnaam"
                />
                @error('companyname') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Voornaam *</label>
                    <input
                        type="text"
                        id="first_name"
                        wire:model="first_name"
                        class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Uw voornaam"
                    />
                    @error('first_name') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Achternaam *</label>
                    <input
                        type="text"
                        id="last_name"
                        wire:model="last_name"
                        class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Uw achternaam"
                    />
                    @error('last_name') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="phonenumber" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Telefoonnummer *</label>
                <input
                    type="tel"
                    id="phonenumber"
                    wire:model="phonenumber"
                    class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="+31 6 12345678"
                />
                @error('phonenumber') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>
                    <svg class="size-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Verstuur terugbelverzoek
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Bezig met verzenden...
                </span>
            </button>
        </form>
    </div>
</div>
