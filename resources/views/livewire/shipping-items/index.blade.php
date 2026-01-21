<?php

use App\Models\ShippingItem;
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

    public function delete(ShippingItem $shippingItem): void
    {
        $this->authorize('shipping-items.destroy');

        $shippingItem->delete();
        $this->dispatch('shipping-item-deleted');
    }

    public function with(): array
    {
        return [
            'shippingItems' => ShippingItem::query()
                ->with(['user', 'account', 'status.colorScheme'])
                ->when($this->search, fn($query) =>
                    $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('account', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                        ->orWhere('delivery_city', 'like', "%{$this->search}%")
                        ->orWhere('pickup_city', 'like', "%{$this->search}%")
                )
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Verzendingen') }}</flux:heading>
            <flux:subheading>{{ __('Beheer verzendingen') }}</flux:subheading>
        </div>
        @can('shipping-items.create')
            <flux:button :href="route('shipping-items.create')" wire:navigate variant="primary" icon="plus">
                {{ __('Nieuwe Verzending') }}
            </flux:button>
        @endcan
    </div>

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="{{ __('Zoek verzendingen...') }}"
        icon="magnifying-glass"
    />

    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Gebruiker') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Account') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Levering') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Totaal') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Status') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Acties') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($shippingItems as $item)
                    <tr wire:key="shipping-item-{{ $item->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4">
                            <flux:text class="text-sm">{{ $item->user?->name ?? '-' }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <flux:text class="text-sm">{{ $item->account?->name ?? '-' }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $item->delivery_city }}</div>
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $item->delivery_date?->format('d-m-Y') }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <flux:text class="font-mono text-sm">€ {{ number_format($item->total_price, 2, ',', '.') }}</flux:text>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status)
                                <span class="inline-flex items-center rounded px-2 py-1 text-xs font-medium {{ $item->status->colorScheme?->bg_class ?? 'bg-zinc-100 dark:bg-zinc-800' }} {{ $item->status->colorScheme?->text_class ?? 'text-white' }}">
                                    {{ $item->status->name }}
                                </span>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('shipping-items.edit')
                                    <flux:button size="sm" :href="route('shipping-items.edit', $item)" wire:navigate variant="ghost">
                                        {{ __('Bewerken') }}
                                    </flux:button>
                                @endcan
                                @can('shipping-items.destroy')
                                    <flux:modal.trigger :name="'delete-shipping-item-'.$item->id">
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
                            <flux:text class="text-zinc-500">{{ __('Geen verzendingen gevonden.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $shippingItems->links() }}

    @foreach ($shippingItems as $item)
        <flux:modal :name="'delete-shipping-item-'.$item->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Verzending verwijderen?') }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __('Je staat op het punt om deze verzending te verwijderen.') }}</p>
                        <p>{{ __('Deze actie kan niet ongedaan worden gemaakt.') }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $item->id }})" variant="danger">{{ __('Verwijderen') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
