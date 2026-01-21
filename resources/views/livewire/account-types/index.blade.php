<?php

use App\Models\AccountType;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'list_order';
    public string $sortDirection = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortByField(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete(AccountType $accountType): void
    {
        $this->authorize('account-types.delete');

        $accountType->delete();

        $this->dispatch('account-type-deleted');
    }

    public function with(): array
    {
        return [
            'accountTypes' => AccountType::query()
                ->with(['colorScheme', 'tax'])
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                )
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Accounttypes') }}</flux:heading>
            <flux:subheading>{{ __('Beheer je accounttypes en prijzen') }}</flux:subheading>
        </div>

        @can('account-types.create')
            <flux:button icon="plus" :href="route('account-types.create')" wire:navigate variant="primary">
                {{ __('Nieuw accounttype') }}
            </flux:button>
        @endcan
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoek accounttypes...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th wire:click="sortByField('list_order')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Volgorde') }}
                        @if($sortBy === 'list_order')
                            <flux:icon.{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }} class="inline h-4 w-4" />
                        @endif
                    </th>
                    <th wire:click="sortByField('name')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Naam') }}
                        @if($sortBy === 'name')
                            <flux:icon.{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }} class="inline h-4 w-4" />
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Prijs/maand') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Prijs/kwartaal') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Prijs/jaar') }}
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
                @forelse($accountTypes as $accountType)
                    <tr wire:key="account-type-{{ $accountType->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text>{{ $accountType->list_order }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($accountType->colorScheme)
                                    <span class="inline-block h-4 w-4 rounded-full {{ $accountType->colorScheme->bg_class }}"></span>
                                @endif
                                <flux:text class="font-medium">{{ $accountType->name }}</flux:text>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text>€ {{ number_format($accountType->price_month, 2, ',', '.') }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text>€ {{ number_format($accountType->price_quarter, 2, ',', '.') }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text>€ {{ number_format($accountType->price_year, 2, ',', '.') }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:badge :variant="$accountType->active ? 'success' : 'warning'">
                                {{ $accountType->active ? __('Actief') : __('Inactief') }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('account-types.edit')
                                    <flux:button size="sm" :href="route('account-types.edit', $accountType)" wire:navigate variant="ghost">
                                        {{ __('Bewerken') }}
                                    </flux:button>
                                @endcan
                                @can('account-types.delete')
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        wire:click="delete({{ $accountType->id }})"
                                        wire:confirm="{{ __('Weet je zeker dat je dit accounttype wilt verwijderen?') }}"
                                    >
                                        {{ __('Verwijderen') }}
                                    </flux:button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('Geen accounttypes gevonden.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $accountTypes->links() }}
    </div>
</div>
