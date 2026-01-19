<?php

use App\Models\TicketStatus;
use App\Models\ColorScheme;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?TicketStatus $ticketStatus = null;

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
        if ($this->ticketStatus) {
            $this->name = $this->ticketStatus->name;
            $this->active = $this->ticketStatus->active;
            $this->color_scheme_id = $this->ticketStatus->color_scheme_id ? (string) $this->ticketStatus->color_scheme_id : null;
            $this->class = $this->ticketStatus->class ?? '';
            $this->list_order = (string) $this->ticketStatus->list_order;
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

        if ($this->ticketStatus) {
            $this->ticketStatus->update($validated);
            $message = 'Ticket status updated successfully.';
        } else {
            TicketStatus::create($validated);
            $message = 'Ticket status created successfully.';
        }

        $this->dispatch('ticket-status-saved');

        $this->redirect(route('ticket-statuses.index'), navigate: true);
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
        <flux:heading size="xl">{{ $ticketStatus ? __('Edit Ticket Status') : __('Create Ticket Status') }}</flux:heading>
        <flux:subheading>{{ $ticketStatus ? __('Update ticket status details') : __('Add a new ticket status') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            :placeholder="__('Open, In Progress, Closed...')"
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
            <flux:button variant="ghost" :href="route('ticket-statuses.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $ticketStatus ? __('Update Status') : __('Create Status') }}
            </flux:button>
        </div>
    </form>
</div>
