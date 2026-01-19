<?php

use App\Models\TicketType;
use App\Models\ColorScheme;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?TicketType $ticketType = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public bool $active = true;

    #[Modelable]
    public ?string $color_scheme_id = null;

    #[Modelable]
    public string $class = '';

    #[Modelable]
    public string $list_order = '0';

    public function mount(): void
    {
        if ($this->ticketType) {
            $this->name = $this->ticketType->name;
            $this->active = $this->ticketType->active;
            $this->color_scheme_id = $this->ticketType->color_scheme_id ? (string) $this->ticketType->color_scheme_id : null;
            $this->class = $this->ticketType->class ?? '';
            $this->list_order = (string) $this->ticketType->list_order;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'active' => ['boolean'],
            'color_scheme_id' => ['nullable', 'exists:color_schemes,id'],
            'class' => ['nullable', 'string', 'max:255'],
            'list_order' => ['required', 'integer', 'min:0'],
        ]);

        if ($this->ticketType) {
            $this->ticketType->update($validated);
            $message = 'Ticket type updated successfully.';
        } else {
            TicketType::create($validated);
            $message = 'Ticket type created successfully.';
        }

        $this->dispatch('ticket-type-saved');

        $this->redirect(route('ticket-types.index'), navigate: true);
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
        <flux:heading size="xl">{{ $ticketType ? __('Edit Ticket Type') : __('Create Ticket Type') }}</flux:heading>
        <flux:subheading>{{ $ticketType ? __('Update ticket type details') : __('Add a new ticket type') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            :placeholder="__('Bug, Feature, Question...')"
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
            wire:model="class"
            :label="__('Custom CSS Class')"
            type="text"
            :placeholder="__('bg-blue-500, bg-green-500...')"
            :description="__('Alleen gebruiken als je geen kleurencombinatie selecteert')"
        />

        <flux:input
            wire:model="list_order"
            :label="__('List Order')"
            type="number"
            min="0"
            required
            :placeholder="__('0')"
        />

        <flux:checkbox wire:model="active" :label="__('Active')" />

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('ticket-types.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $ticketType ? __('Update Type') : __('Create Type') }}
            </flux:button>
        </div>
    </form>
</div>
