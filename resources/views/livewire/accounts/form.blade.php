<?php

use App\Models\Account;
use App\WalletStatus;
use Livewire\Volt\Component;

new class extends Component {
    public ?Account $account = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $company_name = '';

    #[Modelable]
    public string $street_name = '';

    #[Modelable]
    public string $house_number = '';

    #[Modelable]
    public string $zipcode = '';

    #[Modelable]
    public string $city = '';

    #[Modelable]
    public string $email_address = '';

    #[Modelable]
    public string $telephone_number = '';

    #[Modelable]
    public string $kvk = '';

    #[Modelable]
    public string $btw = '';

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
            $this->company_name = $this->account->company_name ?? '';
            $this->street_name = $this->account->street_name ?? '';
            $this->house_number = $this->account->house_number ?? '';
            $this->zipcode = $this->account->zipcode ?? '';
            $this->city = $this->account->city ?? '';
            $this->email_address = $this->account->email_address ?? '';
            $this->telephone_number = $this->account->telephone_number ?? '';
            $this->kvk = $this->account->kvk ?? '';
            $this->btw = $this->account->btw ?? '';
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
            'company_name' => ['nullable', 'string', 'max:255'],
            'street_name' => ['nullable', 'string', 'max:255'],
            'house_number' => ['nullable', 'string', 'max:50'],
            'zipcode' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'email_address' => ['nullable', 'email', 'max:255'],
            'telephone_number' => ['nullable', 'string', 'max:50'],
            'kvk' => ['nullable', 'string', 'max:50'],
            'btw' => ['nullable', 'string', 'max:50'],
            'currency' => ['required', 'string', 'size:3'],
            'timezone' => ['required', 'string'],
            'credit_limit_cents' => ['required', 'integer', 'min:0'],
            'balance_cents' => ['required', 'integer', 'min:0'],
            'wallet_status' => ['required', 'string', 'in:active,suspended,closed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'company_name' => $validated['company_name'],
            'street_name' => $validated['street_name'],
            'house_number' => $validated['house_number'],
            'zipcode' => $validated['zipcode'],
            'city' => $validated['city'],
            'email_address' => $validated['email_address'],
            'telephone_number' => $validated['telephone_number'],
            'kvk' => $validated['kvk'],
            'btw' => $validated['btw'],
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
                    :placeholder="__('Account Name')"
                />

                <flux:separator text="{{ __('Bedrijfsgegevens') }}" />

                <flux:input
                    wire:model="company_name"
                    :label="__('Bedrijfsnaam')"
                    type="text"
                    :placeholder="__('Bedrijfsnaam')"
                />

                <div class="grid gap-6 md:grid-cols-2">
                    <flux:input
                        wire:model="kvk"
                        :label="__('KvK nummer')"
                        type="text"
                        :placeholder="__('12345678')"
                    />

                    <flux:input
                        wire:model="btw"
                        :label="__('BTW nummer')"
                        type="text"
                        :placeholder="__('NL123456789B01')"
                    />
                </div>

                <flux:separator text="{{ __('Contactgegevens') }}" />

                <div class="grid gap-6 md:grid-cols-2">
                    <flux:input
                        wire:model="email_address"
                        :label="__('E-mailadres')"
                        type="email"
                        :placeholder="__('info@bedrijf.nl')"
                    />

                    <flux:input
                        wire:model="telephone_number"
                        :label="__('Telefoonnummer')"
                        type="text"
                        :placeholder="__('+31 6 12345678')"
                    />
                </div>

                <flux:separator text="{{ __('Adresgegevens') }}" />

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <flux:input
                            wire:model="street_name"
                            :label="__('Straatnaam')"
                            type="text"
                            :placeholder="__('Hoofdstraat')"
                        />
                    </div>

                    <flux:input
                        wire:model="house_number"
                        :label="__('Huisnummer')"
                        type="text"
                        :placeholder="__('123')"
                    />
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <flux:input
                        wire:model="zipcode"
                        :label="__('Postcode')"
                        type="text"
                        :placeholder="__('1234 AB')"
                    />

                    <flux:input
                        wire:model="city"
                        :label="__('Plaats')"
                        type="text"
                        :placeholder="__('Amsterdam')"
                    />
                </div>

                <flux:separator text="{{ __('Systeeminstellingen') }}" />

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
