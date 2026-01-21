<?php

use App\Models\Account;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

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

    public function delete(Account $account): void
    {
        $this->authorize('accounts.delete');

        $account->delete();

        $this->dispatch('account-deleted');
    }

    public function with(): array
    {
        return [
            'accounts' => Account::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('company_name', 'like', "%{$this->search}%")
                        ->orWhere('email_address', 'like', "%{$this->search}%")
                        ->orWhere('city', 'like', "%{$this->search}%")
                )
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl">{{ __('Accounts') }}</flux:heading>
                    <flux:subheading>{{ __('Manage your customer accounts') }}</flux:subheading>
                </div>

                @can('accounts.create')
                    <flux:button icon="plus" :href="route('accounts.create')" wire:navigate variant="primary">
                        {{ __('New Account') }}
                    </flux:button>
                @endcan
            </div>

            <div class="flex items-center gap-4">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search accounts...') }}"
                    icon="magnifying-glass"
                    class="flex-1"
                />
            </div>

            <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th wire:click="sortByField('name')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('Name') }}
                                @if($sortBy === 'name')
                                    <flux:icon.{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }} class="inline h-4 w-4" />
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('Contact') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('Plaats') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('Balance') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('Status') }}
                            </th>
                            <th wire:click="sortByField('created_at')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('Created') }}
                                @if($sortBy === 'created_at')
                                    <flux:icon.{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }} class="inline h-4 w-4" />
                                @endif
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                        @forelse($accounts as $account)
                            <tr wire:key="account-{{ $account->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div>
                                        <flux:text class="font-medium">{{ $account->name }}</flux:text>
                                        @if($account->company_name && $account->company_name !== $account->name)
                                            <flux:text class="text-xs text-zinc-500">{{ $account->company_name }}</flux:text>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm">
                                        @if($account->email_address)
                                            <flux:text class="block">{{ $account->email_address }}</flux:text>
                                        @endif
                                        @if($account->telephone_number)
                                            <flux:text class="text-xs text-zinc-500">{{ $account->telephone_number }}</flux:text>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($account->city)
                                        <flux:text>{{ $account->city }}</flux:text>
                                    @else
                                        <flux:text class="text-zinc-400">-</flux:text>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <flux:text>€ {{ number_format($account->balance_cents / 100, 2) }}</flux:text>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <flux:badge :variant="$account->wallet_status->value === 'active' ? 'success' : 'warning'">
                                        {{ ucfirst($account->wallet_status->value) }}
                                    </flux:badge>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <flux:text>{{ $account->created_at->format('d M Y') }}</flux:text>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('accounts.edit')
                                            <flux:button size="sm" :href="route('accounts.edit', $account)" wire:navigate variant="ghost">
                                                {{ __('Edit') }}
                                            </flux:button>
                                        @endcan
                                        @can('accounts.delete')
                                            <flux:button
                                                size="sm"
                                                variant="danger"
                                                wire:click="delete({{ $account->id }})"
                                                wire:confirm="{{ __('Are you sure you want to delete this account?') }}"
                                            >
                                                {{ __('Delete') }}
                                            </flux:button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <flux:text class="text-zinc-500">{{ __('No accounts found.') }}</flux:text>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        <div>
            {{ $accounts->links() }}
        </div>
</div>
