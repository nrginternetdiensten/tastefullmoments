<?php

use App\Models\{AccountType, ColorScheme, InvoiceTax};
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?AccountType $accountType = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $price_month = '0.00';

    #[Modelable]
    public string $price_quarter = '0.00';

    #[Modelable]
    public string $price_year = '0.00';

    #[Modelable]
    public ?string $tax_id = null;

    #[Modelable]
    public string $list_order = '10';

    #[Modelable]
    public bool $active = true;

    #[Modelable]
    public ?string $color_scheme_id = null;

    public function mount(): void
    {
        if ($this->accountType) {
            $this->name = $this->accountType->name;
            $this->price_month = (string) $this->accountType->price_month;
            $this->price_quarter = (string) $this->accountType->price_quarter;
            $this->price_year = (string) $this->accountType->price_year;
            $this->tax_id = $this->accountType->tax_id ? (string) $this->accountType->tax_id : null;
            $this->list_order = (string) $this->accountType->list_order;
            $this->active = $this->accountType->active;
            $this->color_scheme_id = $this->accountType->color_scheme_id ? (string) $this->accountType->color_scheme_id : null;
        }
    }

    public function save(): void
    {
        $this->authorize($this->accountType ? 'account-types.edit' : 'account-types.create');

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_month' => ['required', 'numeric', 'min:0'],
            'price_quarter' => ['required', 'numeric', 'min:0'],
            'price_year' => ['required', 'numeric', 'min:0'],
            'tax_id' => ['nullable', 'exists:invoice_taxes,id'],
            'list_order' => ['required', 'integer', 'min:0'],
            'active' => ['boolean'],
            'color_scheme_id' => ['nullable', 'exists:color_schemes,id'],
        ]);

        if ($this->accountType) {
            $this->accountType->update($validated);
        } else {
            AccountType::create($validated);
        }

        $this->dispatch('account-type-saved');
        $this->redirect(route('account-types.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'colorSchemes' => ColorScheme::where('active', true)->orderBy('list_order')->get(),
            'taxes' => InvoiceTax::where('active', true)->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $accountType ? __('Accounttype bewerken') : __('Accounttype aanmaken') }}</flux:heading>
        <flux:subheading>{{ $accountType ? __('Wijzig accounttype details') : __('Voeg een nieuw accounttype toe') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Naam')"
            type="text"
            required
            autofocus
            :placeholder="__('Basic, Professional, Enterprise...')"
        />

        <flux:separator text="{{ __('Prijzen') }}" />

        <div class="grid gap-6 md:grid-cols-3">
            <flux:input
                wire:model="price_month"
                :label="__('Prijs per maand')"
                type="number"
                step="0.01"
                min="0"
                required
                :placeholder="__('0.00')"
            />

            <flux:input
                wire:model="price_quarter"
                :label="__('Prijs per kwartaal')"
                type="number"
                step="0.01"
                min="0"
                required
                :placeholder="__('0.00')"
            />

            <flux:input
                wire:model="price_year"
                :label="__('Prijs per jaar')"
                type="number"
                step="0.01"
                min="0"
                required
                :placeholder="__('0.00')"
            />
        </div>

        <flux:separator text="{{ __('Instellingen') }}" />

        <flux:field>
            <flux:label>{{ __('BTW tarief') }}</flux:label>
            <flux:select wire:model="tax_id" placeholder="{{ __('Selecteer BTW tarief') }}">
                @foreach($taxes as $tax)
                    <flux:select.option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->percentage }}%)</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="tax_id" />
        </flux:field>

        <flux:input
            wire:model="list_order"
            :label="__('Sorteervolgorde')"
            type="number"
            min="0"
            required
        />

        <div>
            <flux:checkbox wire:model="active" :label="__('Actief')" />
        </div>

        <flux:field>
            <flux:label>{{ __('Kleurenschema') }}</flux:label>
            <flux:description>{{ __('Optioneel: Selecteer een voorgedefinieerde kleurencombinatie') }}</flux:description>

            <div class="mt-2 flex max-h-48 flex-wrap gap-2 overflow-y-auto rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <label class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border-2 transition {{ !$color_scheme_id ? 'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800' : 'border-zinc-300 hover:border-zinc-400 dark:border-zinc-600 dark:hover:border-zinc-500' }} bg-zinc-100 dark:bg-zinc-800" title="Geen kleurencombinatie">
                    <input type="radio" wire:model.live="color_scheme_id" value="" class="sr-only">
                    <span class="text-xl text-zinc-400">×</span>
                </label>

                @foreach($colorSchemes as $scheme)
                    <label class="h-10 w-10 cursor-pointer rounded-full border-2 transition {{ $color_scheme_id == $scheme->id ? 'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800' : 'border-white hover:border-zinc-300 dark:border-zinc-900 dark:hover:border-zinc-600' }} {{ $scheme->bg_class }}" title="{{ $scheme->name }}">
                        <input type="radio" wire:model.live="color_scheme_id" value="{{ $scheme->id }}" class="sr-only">
                    </label>
                @endforeach
            </div>
        </flux:field>

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('account-types.index')" wire:navigate>
                {{ __('Annuleren') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $accountType ? __('Accounttype bijwerken') : __('Accounttype aanmaken') }}
            </flux:button>
        </div>
    </form>
</div>
