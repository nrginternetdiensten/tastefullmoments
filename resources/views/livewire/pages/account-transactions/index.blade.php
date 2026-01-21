<?php

use App\Models\{AccountTransaction, Account};
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $account_id = null;
    public ?string $type = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAccountId(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function delete(AccountTransaction $transaction): void
    {
        $transaction->delete();
    }

    public function with(): array
    {
        return [
            'transactions' => AccountTransaction::query()
                ->with('account')
                ->when($this->search, fn($query) =>
                    $query->where('description', 'like', "%{$this->search}%")
                        ->orWhere('reference', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%")
                )
                ->when($this->account_id, fn($query) =>
                    $query->where('account_id', $this->account_id)
                )
                ->when($this->type, fn($query) =>
                    $query->where('type', $this->type)
                )
                ->latest('transaction_date')
                ->paginate(10),
            'accounts' => Account::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Account Transactions') }}</flux:heading>
            <flux:subheading>{{ __('Manage debits and credits') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('account-transactions.create')" wire:navigate variant="primary">
            {{ __('New Transaction') }}
        </flux:button>
    </div>

    <div class="space-y-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search transactions...') }}"
            icon="magnifying-glass"
        />

        <div class="flex flex-col gap-4 sm:flex-row">
            <flux:select
                wire:model.live="account_id"
                placeholder="{{ __('All accounts') }}"
                class="flex-1"
            >
                <option value="">{{ __('All accounts') }}</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="type"
                placeholder="{{ __('All types') }}"
                class="flex-1"
            >
                <option value="">{{ __('All types') }}</option>
                <option value="debit">{{ __('Debit') }}</option>
                <option value="credit">{{ __('Credit') }}</option>
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Date') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Account') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Description') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Type') }}
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Amount') }}
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse($transactions as $transaction)
                        <tr wire:key="transaction-{{ $transaction->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-zinc-900 dark:text-zinc-100 lg:px-6">
                                {{ $transaction->transaction_date->format('d-m-Y H:i') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-900 dark:text-zinc-100 lg:px-6">
                                {{ $transaction->account->name }}
                            </td>
                            <td class="px-4 py-4 lg:px-6">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $transaction->description }}
                                    </div>
                                    @if($transaction->reference)
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ __('Ref:') }} {{ $transaction->reference }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm lg:px-6">
                                @if($transaction->type === 'debit')
                                    <flux:badge color="red" size="sm">{{ __('Debit') }}</flux:badge>
                                @else
                                    <flux:badge color="green" size="sm">{{ __('Credit') }}</flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium lg:px-6">
                                <span class="{{ $transaction->type === 'debit' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    {{ $transaction->type === 'debit' ? '-' : '+' }}€{{ number_format($transaction->amount_cents / 100, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium lg:px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        :href="route('account-transactions.edit', $transaction)"
                                        wire:navigate
                                    />
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="delete({{ $transaction->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this transaction?') }}"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400 lg:px-6">
                                {{ __('No transactions found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
