<?php

use App\Models\Invoice;
use App\Models\InvoiceStatus;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public ?string $statusFilter = null;
    public ?string $yearFilter = null;
    public ?string $monthFilter = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }
    
    public function updatingYearFilter(): void
    {
        $this->resetPage();
    }
    
    public function updatingMonthFilter(): void
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

    public function delete(Invoice $invoice): void
    {
        $this->authorize('invoices.delete');
        
        $invoice->delete();
        $this->dispatch('invoice-deleted');
    }

    public function with(): array
    {
        return [
            'invoices' => Invoice::query()
                ->with(['status.colorScheme', 'account', 'user'])
                ->when($this->search, fn($query) =>
                    $query->where('invoice_id', 'like', "%{$this->search}%")
                        ->orWhereHas('account', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                )
                ->when($this->statusFilter, fn($query) =>
                    $query->where('status_id', $this->statusFilter)
                )
                ->when($this->yearFilter, fn($query) =>
                    $query->whereYear('created_at', $this->yearFilter)
                )
                ->when($this->monthFilter, fn($query) =>
                    $query->whereMonth('created_at', $this->monthFilter)
                )
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15),
            'statuses' => InvoiceStatus::query()
                ->where('active', true)
                ->orderBy('list_order')
                ->get(),
            'years' => Invoice::query()
                ->selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year'),
            'months' => [
                1 => 'Januari',
                2 => 'Februari', 
                3 => 'Maart',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Augustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'December',
            ],
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Facturen') }}</flux:heading>
            <flux:subheading>{{ __('Beheer facturen') }}</flux:subheading>
        </div>
        @can('invoices.create')
            <flux:button :href="route('invoices.create')" wire:navigate variant="primary" icon="plus">
                {{ __('Nieuwe Factuur') }}
            </flux:button>
        @endcan
    </div>

    <div class="space-y-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoek facturen...') }}"
            icon="magnifying-glass"
        />
        
        <div class="flex items-center gap-4">
            <flux:select wire:model.live="statusFilter" placeholder="{{ __('Alle statussen') }}">
                <flux:select.option value="">{{ __('Alle statussen') }}</flux:select.option>
                @foreach($statuses as $status)
                    <flux:select.option value="{{ $status->id }}">{{ $status->name }}</flux:select.option>
                @endforeach
            </flux:select>
            
            <flux:select wire:model.live="yearFilter" placeholder="{{ __('Alle jaren') }}">
                <flux:select.option value="">{{ __('Alle jaren') }}</flux:select.option>
                @foreach($years as $year)
                    <flux:select.option value="{{ $year }}">{{ $year }}</flux:select.option>
                @endforeach
            </flux:select>
            
            <flux:select wire:model.live="monthFilter" placeholder="{{ __('Alle maanden') }}">
                <flux:select.option value="">{{ __('Alle maanden') }}</flux:select.option>
                @foreach($months as $monthNumber => $monthName)
                    <flux:select.option value="{{ $monthNumber }}">{{ $monthName }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th
                        wire:click="sortByField('invoice_id')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Factuurnummer') }}
                            @if($sortField === 'invoice_id')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Account') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Gebruiker') }}
                    </th>
                    <th
                        wire:click="sortByField('total')"
                        class="cursor-pointer px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center justify-end gap-1">
                            {{ __('Totaal') }}
                            @if($sortField === 'total')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Status') }}
                    </th>
                    <th
                        wire:click="sortByField('created_at')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Aangemaakt') }}
                            @if($sortField === 'created_at')
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
                @forelse($invoices as $invoice)
                    <tr wire:key="invoice-{{ $invoice->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4">
                            <flux:text class="font-medium">{{ $invoice->invoice_id }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm">{{ $invoice->account?->name ?? '-' }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm">{{ $invoice->user?->name ?? '-' }}</flux:text>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <flux:text class="font-mono text-sm">€ {{ number_format($invoice->total, 2, ',', '.') }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->status)
                                <span class="inline-flex items-center rounded px-2 py-1 text-xs font-medium {{ $invoice->status->colorScheme?->bg_class ?? $invoice->status->class ?? 'bg-zinc-100 dark:bg-zinc-800' }} {{ $invoice->status->colorScheme?->text_class ?? 'text-white' }}">
                                    {{ $invoice->status->name }}
                                </span>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm">{{ $invoice->created_at->format('d-m-Y') }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('invoices.edit')
                                    <flux:button size="sm" :href="route('invoices.edit', $invoice)" wire:navigate variant="ghost">
                                        {{ __('Bewerken') }}
                                    </flux:button>
                                @endcan
                                @can('invoices.delete')
                                    <flux:modal.trigger :name="'delete-invoice-'.$invoice->id">
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
                        <td colspan="7" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('Geen facturen gevonden.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $invoices->links() }}
    </div>

    @foreach ($invoices as $invoice)
        <flux:modal :name="'delete-invoice-'.$invoice->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Factuur verwijderen?') }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __('Je staat op het punt om factuur ') }}{{ $invoice->invoice_id }}{{ __(' te verwijderen.') }}</p>
                        <p>{{ __('Deze actie kan niet ongedaan worden gemaakt.') }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $invoice->id }})" variant="danger">{{ __('Verwijderen') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
