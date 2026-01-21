<?php

use App\Models\{ColorScheme, OrderStatus};
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?OrderStatus $orderStatus = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public bool $active = true;

    #[Modelable]
    public ?string $color_scheme_id = null;

    #[Modelable]
    public string $list_order = '10';

    public function mount(): void
    {
        if ($this->orderStatus) {
            $this->name = $this->orderStatus->name;
            $this->active = $this->orderStatus->active;
            $this->color_scheme_id = $this->orderStatus->color_scheme_id ? (string) $this->orderStatus->color_scheme_id : null;
            $this->list_order = (string) $this->orderStatus->list_order;
        }
    }

    public function save(): void
    {
        $this->authorize($this->orderStatus ? 'order-statuses.edit' : 'order-statuses.create');

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'active' => ['boolean'],
            'color_scheme_id' => ['nullable', 'exists:color_schemes,id'],
            'list_order' => ['required', 'integer', 'min:0'],
        ]);

        if ($this->orderStatus) {
            $this->orderStatus->update($validated);
        } else {
            OrderStatus::create($validated);
        }

        $this->dispatch('order-status-saved');
        $this->redirect(route('order-statuses.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'colorSchemes' => ColorScheme::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $orderStatus ? __('Orderstatus bewerken') : __('Orderstatus aanmaken') }}</flux:heading>
        <flux:subheading>{{ $orderStatus ? __('Wijzig orderstatus details') : __('Voeg een nieuwe orderstatus toe') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Naam')"
            type="text"
            required
            autofocus
            :placeholder="__('In behandeling, Verzonden, Afgerond...')"
        />

        <flux:field>
            <flux:label>{{ __('Color Scheme') }}</flux:label>
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

        <flux:input
            wire:model="list_order"
            :label="__('Volgorde')"
            type="number"
            min="0"
            required
            :placeholder="__('10')"
        />

        <flux:checkbox wire:model="active" :label="__('Actief')" />

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('order-statuses.index')" wire:navigate>
                {{ __('Annuleren') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $orderStatus ? __('Status bijwerken') : __('Status aanmaken') }}
            </flux:button>
        </div>
    </form>
</div>
