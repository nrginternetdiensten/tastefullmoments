<?php

use App\Models\Account;
use App\WalletStatus;
use Livewire\Volt\Component;

new class extends Component {
    public ?Account $account = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $currency = '';

    #[Modelable]
    public string $timezone = '';

    #[Modelable]
    public int $credit_limit_cents = 0;

    #[Modelable]
    public int $balance_cents = 0;

    #[Modelable]
    public string $wallet_status = 'active';

    public function mount(): void
    {
        if ($this->account) {
            $this->name = $this->account->name;
            $this->currency = $this->account->country_defaults['currency'] ?? '';
            $this->timezone = $this->account->country_defaults['timezone'] ?? '';
            $this->credit_limit_cents = $this->account->credit_limit_cents;
            $this->balance_cents = $this->account->balance_cents;
            $this->wallet_status = $this->account->wallet_status->value;
        }
    }

    public function save(): void
    {
        $this->authorize($this->account ? 'accounts.edit' : 'accounts.create');
        
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'size:3'],
            'timezone' => ['required', 'string'],
            'credit_limit_cents' => ['required', 'integer', 'min:0'],
            'balance_cents' => ['required', 'integer', 'min:0'],
            'wallet_status' => ['required', 'string', 'in:active,suspended,closed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'country_defaults' => [
                'currency' => $validated['currency'],
                'timezone' => $validated['timezone'],
            ],
            'credit_limit_cents' => $validated['credit_limit_cents'],
            'balance_cents' => $validated['balance_cents'],
            'wallet_status' => $validated['wallet_status'],
        ];

        if ($this->account) {
            $this->account->update($data);
            $this->dispatch('account-updated');
        } else {
            Account::create($data);
            $this->dispatch('account-created');
        }

        $this->redirect(route('accounts.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
            <div>
                <flux:heading size="xl">{{ $account ? __('Edit Account') : __('Create Account') }}</flux:heading>
                <flux:subheading>{{ $account ? __('Update account details') : __('Add a new customer account') }}</flux:subheading>
            </div>

            <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:input
                    wire:model="name"
                    :label="__('Account Name')"
                    type="text"
                    required
                    autofocus
                    :placeholder="__('Company Name')"
                />

                <div class="grid gap-6 md:grid-cols-2">
                    <flux:input
                        wire:model="currency"
                        :label="__('Currency')"
                        type="text"
                        required
                        maxlength="3"
                        :placeholder="__('EUR')"
                    />

                    <flux:input
                        wire:model="timezone"
                        :label="__('Timezone')"
                        type="text"
                        required
                        :placeholder="__('Europe/Amsterdam')"
                    />
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <flux:input
                            wire:model="credit_limit_cents"
                            :label="__('Credit Limit (cents)')"
                            type="number"
                            required
                            min="0"
                            step="1"
                        />
                        <flux:text class="mt-1 text-xs text-zinc-500">{{ __('Amount in cents (€10.00 = 1000 cents)') }}</flux:text>
                    </div>

                    <div>
                        <flux:input
                            wire:model="balance_cents"
                            :label="__('Balance (cents)')"
                            type="number"
                            required
                            min="0"
                            step="1"
                        />
                        <flux:text class="mt-1 text-xs text-zinc-500">{{ __('Current balance in cents (€10.00 = 1000 cents)') }}</flux:text>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Wallet Status') }}</label>
                    <flux:radio.group wire:model="wallet_status" variant="segmented">
                        <flux:radio value="active">{{ __('Active') }}</flux:radio>
                        <flux:radio value="suspended">{{ __('Suspended') }}</flux:radio>
                        <flux:radio value="closed">{{ __('Closed') }}</flux:radio>
                    </flux:radio.group>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <flux:button variant="ghost" :href="route('accounts.index')" wire:navigate>
                        {{ __('Cancel') }}
                    </flux:button>

                    <flux:button type="submit" variant="primary">
                        {{ $account ? __('Update Account') : __('Create Account') }}
                    </flux:button>
                </div>
            </form>
</div>
