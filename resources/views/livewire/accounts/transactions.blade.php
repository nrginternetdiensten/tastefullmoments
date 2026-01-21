<?php

use App\Models\Account;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Account $account;
    public string $search = '';
    public ?string $type = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'transactions' => $this->account->transactions()
                ->when($this->search, fn($query) =>
                    $query->where('description', 'like', "%{$this->search}%")
                        ->orWhere('reference', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%")
                )
                ->when($this->type, fn($query) =>
                    $query->where('type', $this->type)
                )
                ->latest('transaction_date')
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="lg">{{ __('Transactions') }}</flux:heading>
            <flux:subheading>{{ __('Account transaction history') }}</flux:subheading>
        </div>
        <flux:button
            :href="route('account-transactions.create', ['account' => $account->id])"
            wire:navigate
            variant="primary"
            icon="plus"
        >
            {{ __('New Transaction') }}
        </flux:button>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search transactions...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />

        <flux:select
            wire:model.live="type"
            placeholder="{{ __('All types') }}"
            class="sm:w-48"
        >
            <option value="">{{ __('All types') }}</option>
            <option value="debit">{{ __('Debit') }}</option>
            <option value="credit">{{ __('Credit') }}</option>
        </flux:select>
    </div>

    @if($transactions->count() > 0)
        <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                                {{ __('Date') }}
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
                        @foreach($transactions as $transaction)
                            <tr wire:key="transaction-{{ $transaction->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-zinc-900 dark:text-zinc-100 lg:px-6">
                                    {{ $transaction->transaction_date->format('d-m-Y H:i') }}
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
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        :href="route('account-transactions.edit', $transaction)"
                                        wire:navigate
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="rounded-lg border border-zinc-200 bg-white px-6 py-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
            <flux:icon.banknotes class="mx-auto size-12 text-zinc-400" />
            <flux:heading size="lg" class="mt-4">{{ __('No transactions found') }}</flux:heading>
            <flux:subheading class="mt-2">
                {{ __('This account has no transactions yet.') }}
            </flux:subheading>
            <flux:button
                :href="route('account-transactions.create', ['account' => $account->id])"
                wire:navigate
                variant="primary"
                class="mt-6"
            >
                {{ __('Create First Transaction') }}
            </flux:button>
        </div>
    @endif
</div>
