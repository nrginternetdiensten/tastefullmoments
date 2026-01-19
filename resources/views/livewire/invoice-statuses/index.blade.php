<?php

use App\Models\InvoiceStatus;
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

    public function delete(InvoiceStatus $invoiceStatus): void
    {
        $this->authorize('invoice-statuses.delete');
        
        $invoiceStatus->delete();
        $this->dispatch('invoice-status-deleted');
    }

    public function with(): array
    {
        return [
            'invoiceStatuses' => InvoiceStatus::query()
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
            <flux:heading size="xl">{{ __('Invoice Statussen') }}</flux:heading>
            <flux:subheading>{{ __('Beheer factuurstatus opties') }}</flux:subheading>
        </div>
        @can('invoice-statuses.create')
            <flux:button :href="route('invoice-statuses.create')" wire:navigate variant="primary" icon="plus">
                {{ __('Nieuwe Status') }}
            </flux:button>
        @endcan
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoek statussen...') }}"
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
                            {{ __('Naam') }}
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
                        {{ __('Acties') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($invoiceStatuses as $invoiceStatus)
                    <tr wire:key="invoice-status-{{ $invoiceStatus->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4">
                            <flux:text class="text-sm font-mono">{{ $invoiceStatus->id }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded px-3 py-1 text-sm font-medium {{ $invoiceStatus->colorScheme?->bg_class ?? $invoiceStatus->class ?? 'bg-zinc-100 dark:bg-zinc-800' }} {{ $invoiceStatus->colorScheme?->text_class ?? 'text-white' }}">
                                {{ $invoiceStatus->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($invoiceStatus->active)
                                <flux:badge color="green" size="sm">{{ __('Actief') }}</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">{{ __('Inactief') }}</flux:badge>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm font-mono">{{ $invoiceStatus->list_order }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('invoice-statuses.edit')
                                    <flux:button size="sm" :href="route('invoice-statuses.edit', $invoiceStatus)" wire:navigate variant="ghost">
                                        {{ __('Bewerken') }}
                                    </flux:button>
                                @endcan
                                @can('invoice-statuses.delete')
                                    <flux:modal.trigger :name="'delete-invoice-status-'.$invoiceStatus->id">
                                        <flux:button size="sm" variant="danger">
                                            {{ __('Verwijderen') }}
                                        </flux:button>
                                    </flux:modal.trigger>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('Geen statussen gevonden.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $invoiceStatuses->links() }}
    </div>

    @foreach ($invoiceStatuses as $invoiceStatus)
        <flux:modal :name="'delete-status-'.$invoiceStatus->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Status verwijderen?') }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __('Je staat op het punt om "') }}{{ $invoiceStatus->name }}{{ __('" te verwijderen.') }}</p>
                        <p>{{ __('Deze actie kan niet ongedaan worden gemaakt.') }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $invoiceStatus->id }})" variant="danger">{{ __('Status verwijderen') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
