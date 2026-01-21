<?php

use App\Models\Order;
use App\Models\OrderStatus;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public ?string $statusFilter = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
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

    public function delete(Order $order): void
    {
        $this->authorize('orders.delete');

        $order->delete();
        $this->dispatch('order-deleted');
    }

    public function with(): array
    {
        return [
            'orders' => Order::query()
                ->with(['status.colorScheme', 'account', 'user'])
                ->when($this->search, fn($query) =>
                    $query->where('order_id', 'like', "%{$this->search}%")
                        ->orWhereHas('account', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                )
                ->when($this->statusFilter, fn($query) =>
                    $query->where('status_id', $this->statusFilter)
                )
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15),
            'statuses' => OrderStatus::query()
                ->where('active', true)
                ->orderBy('list_order')
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Orders') }}</flux:heading>
            <flux:subheading>{{ __('Beheer orders') }}</flux:subheading>
        </div>
        @can('orders.create')
            <flux:button :href="route('orders.create')" wire:navigate variant="primary" icon="plus">
                {{ __('Nieuwe Order') }}
            </flux:button>
        @endcan
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoek orders...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />

        <flux:select wire:model.live="statusFilter" variant="listbox" placeholder="{{ __('Alle statussen') }}" class="w-48">
            <flux:select.option value="">{{ __('Alle statussen') }}</flux:select.option>
            @foreach($statuses as $status)
                <flux:select.option value="{{ $status->id }}">{{ $status->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th
                        wire:click="sortByField('order_id')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Order ID') }}
                            @if($sortField === 'order_id')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Account') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Status') }}
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
                @forelse($orders as $order)
                    <tr wire:key="order-{{ $order->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4">
                            <flux:text class="font-mono font-medium">{{ $order->order_id }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm">{{ $order->account->name }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->status)
                                <span class="inline-flex items-center rounded px-3 py-1 text-sm font-medium {{ $order->status->colorScheme?->bg_class ?? 'bg-zinc-100 dark:bg-zinc-800' }} {{ $order->status->colorScheme?->text_class ?? 'text-zinc-900 dark:text-zinc-100' }}">
                                    {{ $order->status->name }}
                                </span>
                            @else
                                <flux:badge color="zinc" size="sm">{{ __('Geen status') }}</flux:badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <flux:text class="font-mono font-medium">€ {{ number_format($order->total, 2, ',', '.') }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text class="text-sm text-zinc-500">{{ $order->created_at->format('d M Y') }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('orders.edit')
                                    <flux:button size="sm" :href="route('orders.edit', $order)" wire:navigate variant="ghost">
                                        {{ __('Bewerken') }}
                                    </flux:button>
                                @endcan
                                @can('orders.delete')
                                    <flux:modal.trigger :name="'delete-order-'.$order->id">
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
                        <td colspan="6" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('Geen orders gevonden.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $orders->links() }}
    </div>

    @foreach ($orders as $order)
        <flux:modal :name="'delete-order-'.$order->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Order verwijderen?') }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __('Je staat op het punt om order "') }}{{ $order->order_id }}{{ __('" te verwijderen.') }}</p>
                        <p>{{ __('Deze actie kan niet ongedaan worden gemaakt.') }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $order->id }})" variant="danger">{{ __('Order verwijderen') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
