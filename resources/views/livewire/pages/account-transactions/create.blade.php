<?php

use App\Models\{AccountTransaction, Account};
use Livewire\Volt\Component;

new class extends Component {
    public ?int $account_id = null;
    public string $type = 'debit';
    public string $amount_cents = '';
    public string $description = '';
    public ?string $notes = null;
    public ?string $reference = null;
    public string $transaction_date = '';

    public function mount(?int $account = null): void
    {
        $this->transaction_date = now()->format('Y-m-d\TH:i');

        if ($account) {
            $this->account_id = $account;
        }
    }

    public function create(): void
    {
        $validated = $this->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:debit,credit',
            'amount_cents' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'reference' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        // Convert euros to cents
        $validated['amount_cents'] = (int) ($validated['amount_cents'] * 100);

        AccountTransaction::create($validated);

        session()->flash('message', __('Transaction created successfully.'));

        $this->redirect(route('account-transactions.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'accounts' => Account::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">{{ __('New Transaction') }}</flux:heading>
        <flux:subheading>{{ __('Create a new account transaction') }}</flux:subheading>
    </div>

    <form wire:submit="create" class="space-y-6">
        <div class="grid gap-6 sm:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Account') }}</flux:label>
                <flux:select wire:model="account_id" placeholder="{{ __('Select account') }}">
                    <option value="">{{ __('Select account') }}</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="account_id" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Type') }}</flux:label>
                <flux:select wire:model="type">
                    <option value="debit">{{ __('Debit (-)') }}</option>
                    <option value="credit">{{ __('Credit (+)') }}</option>
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Amount (€)') }}</flux:label>
                <flux:input
                    type="number"
                    step="0.01"
                    min="0.01"
                    wire:model="amount_cents"
                    placeholder="0.00"
                />
                <flux:error name="amount_cents" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Transaction Date') }}</flux:label>
                <flux:input
                    type="datetime-local"
                    wire:model="transaction_date"
                />
                <flux:error name="transaction_date" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>{{ __('Description') }}</flux:label>
            <flux:input
                wire:model="description"
                placeholder="{{ __('Enter description') }}"
            />
            <flux:error name="description" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Reference') }}</flux:label>
            <flux:input
                wire:model="reference"
                placeholder="{{ __('Optional reference number') }}"
            />
            <flux:error name="reference" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Notes') }}</flux:label>
            <flux:textarea
                wire:model="notes"
                rows="4"
                placeholder="{{ __('Optional notes') }}"
            />
            <flux:error name="notes" />
        </flux:field>

        <div class="flex items-center justify-between">
            <flux:button variant="ghost" :href="route('account-transactions.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Create Transaction') }}
            </flux:button>
        </div>
    </form>
</div>
