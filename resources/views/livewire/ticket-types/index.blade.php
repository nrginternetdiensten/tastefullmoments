<?php

use App\Models\TicketType;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortField = 'list_order';
    public string $sortDirection = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortByField(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete(TicketType $ticketType): void
    {
        $ticketType->delete();
        $this->dispatch('ticket-type-deleted');
    }

    public function with(): array
    {
        return [
            'ticketTypes' => TicketType::query()
                ->with('colorScheme')
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('class', 'like', "%{$this->search}%")
                )
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Ticket Types') }}</flux:heading>
            <flux:subheading>{{ __('Manage ticket type options') }}</flux:subheading>
        </div>
        <flux:button :href="route('ticket-types.create')" wire:navigate variant="primary" icon="plus">
            {{ __('New Type') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search types...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th
                        wire:click="sortByField('id')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('ID') }}
                            @if($sortField === 'id')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th
                        wire:click="sortByField('name')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Name') }}
                            @if($sortField === 'name')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th
                        wire:click="sortByField('active')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Status') }}
                            @if($sortField === 'active')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th
                        wire:click="sortByField('list_order')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Volgorde') }}
                            @if($sortField === 'list_order')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($ticketTypes as $ticketType)
                    <tr wire:key="ticket-type-{{ $ticketType->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4">
                            <flux:text class="text-sm font-mono">{{ $ticketType->id }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded px-3 py-1 text-sm font-medium {{ $ticketType->colorScheme?->bg_class ?? $ticketType->class ?? 'bg-zinc-100 dark:bg-zinc-800' }} {{ $ticketType->colorScheme?->text_class ?? 'text-white' }}">
                                {{ $ticketType->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($ticketType->active)
                                <flux:badge color="green" size="sm">{{ __('Active') }}</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">{{ __('Inactive') }}</flux:badge>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm font-mono">{{ $ticketType->list_order }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" :href="route('ticket-types.edit', $ticketType)" wire:navigate variant="ghost">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:modal.trigger :name="'delete-type-'.$ticketType->id">
                                    <flux:button size="sm" variant="danger">
                                        {{ __('Delete') }}
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('No ticket types found.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $ticketTypes->links() }}
    </div>

    @foreach ($ticketTypes as $ticketType)
        <flux:modal :name="'delete-type-'.$ticketType->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete type?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete "{{ $ticketType->name }}".</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $ticketType->id }})" variant="danger">Delete type</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
