<?php

use App\Models\InvoiceTax;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

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

    public function delete(InvoiceTax $invoiceTax): void
    {
        $invoiceTax->delete();
        $this->dispatch('invoice-tax-deleted');
    }

    public function with(): array
    {
        return [
            'invoiceTaxes' => InvoiceTax::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                )
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Invoice Taxes') }}</flux:heading>
            <flux:subheading>{{ __('Manage tax rates for invoices') }}</flux:subheading>
        </div>
        <flux:button :href="route('invoice-taxes.create')" wire:navigate variant="primary" icon="plus">
            {{ __('New Tax') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search taxes...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
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
                        wire:click="sortByField('percentage')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Percentage') }}
                            @if($sortField === 'percentage')
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
                        wire:click="sortByField('created_at')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Created') }}
                            @if($sortField === 'created_at')
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
                @forelse($invoiceTaxes as $invoiceTax)
                    <tr wire:key="invoice-tax-{{ $invoiceTax->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4">
                            <flux:text class="font-medium">{{ $invoiceTax->name }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm">{{ number_format($invoiceTax->percentage, 2) }}%</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            @if($invoiceTax->active)
                                <flux:badge color="green" size="sm">{{ __('Active') }}</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">{{ __('Inactive') }}</flux:badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text class="text-sm text-zinc-500">{{ $invoiceTax->created_at->format('d M Y') }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" :href="route('invoice-taxes.edit', $invoiceTax)" wire:navigate variant="ghost">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button
                                    size="sm"
                                    wire:click="delete({{ $invoiceTax->id }})"
                                    wire:confirm="Are you sure you want to delete this tax?"
                                    variant="danger"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('No invoice taxes found.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $invoiceTaxes->links() }}
    </div>
</div>
