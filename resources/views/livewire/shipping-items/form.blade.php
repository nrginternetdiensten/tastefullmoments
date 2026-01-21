<?php

use App\Models\Account;
use App\Models\Country;
use App\Models\ShipmentStatus;
use App\Models\ShippingItem;
use App\Models\User;
use Livewire\Volt\Component;

new class extends Component
{
    public ?ShippingItem $shippingItem = null;

    public $user_id;

    public $account_id;

    public $delivery_date;

    public $delivery_time;

    public $delivery_first_name;

    public $delivery_last_name;

    public $delivery_street;

    public $delivery_housenumber;

    public $delivery_zipcode;

    public $delivery_city;

    public $delivery_country_id;

    public $pickup_date;

    public $pickup_time;

    public $pickup_first_name;

    public $pickup_last_name;

    public $pickup_street;

    public $pickup_housenumber;

    public $pickup_zipcode;

    public $pickup_city;

    public $pickup_country_id;

    public $return_date;

    public $return_time;

    public $return_first_name;

    public $return_last_name;

    public $return_street;

    public $return_housenumber;

    public $return_zipcode;

    public $return_city;

    public $return_country_id;

    public $price_delivery = 0;

    public $price_pickup = 0;

    public $price_return = 0;

    public $total_price = 0;

    public $status_id;

    public $transaction_done = false;

    public $transaction_id;

    public function mount(?ShippingItem $shippingItem = null): void
    {
        $this->shippingItem = $shippingItem;

        if ($shippingItem?->exists) {
            $this->fill($shippingItem->only([
                'user_id', 'account_id', 'delivery_date', 'delivery_time',
                'delivery_first_name', 'delivery_last_name', 'delivery_street',
                'delivery_housenumber', 'delivery_zipcode', 'delivery_city',
                'delivery_country_id', 'pickup_date', 'pickup_time',
                'pickup_first_name', 'pickup_last_name', 'pickup_street',
                'pickup_housenumber', 'pickup_zipcode', 'pickup_city',
                'pickup_country_id', 'return_date', 'return_time',
                'return_first_name', 'return_last_name', 'return_street',
                'return_housenumber', 'return_zipcode', 'return_city',
                'return_country_id', 'price_delivery', 'price_pickup',
                'price_return', 'total_price', 'status_id', 'transaction_done',
                'transaction_id',
            ]));
        }

        $this->calculateTotal();
    }

    public function updatedAccountId(): void
    {
        // Reset user when account changes
        $this->user_id = null;
        $this->calculateTotal();
    }

    public function updatedPriceDelivery(): void
    {
        $this->calculateTotal();
    }

    public function updatedPricePickup(): void
    {
        $this->calculateTotal();
    }

    public function updatedPriceReturn(): void
    {
        $this->calculateTotal();
    }

    protected function calculateTotal(): void
    {
        $this->total_price = ($this->price_delivery ?? 0) + ($this->price_pickup ?? 0) + ($this->price_return ?? 0);
    }

    public function getInsufficientBalanceProperty(): bool
    {
        if (! $this->account_id || ! $this->total_price) {
            return false;
        }

        $account = Account::find($this->account_id);
        if (! $account) {
            return false;
        }

        $totalInCents = $this->total_price * 100;

        return $totalInCents > $account->balance_cents;
    }

    public function save(): void
    {
        $this->authorize($this->shippingItem?->exists ? 'shipping-items.update' : 'shipping-items.store');

        // Check if balance is insufficient
        if ($this->insufficientBalance) {
            $this->addError('total_price', 'Het totaalbedrag is hoger dan het beschikbare saldo.');

            return;
        }

        $validated = $this->validate([
            'user_id' => 'nullable|exists:users,id',
            'account_id' => 'nullable|exists:accounts,id',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable',
            'delivery_first_name' => 'nullable|string|max:255',
            'delivery_last_name' => 'nullable|string|max:255',
            'delivery_street' => 'nullable|string|max:255',
            'delivery_housenumber' => 'nullable|string|max:255',
            'delivery_zipcode' => 'nullable|string|max:255',
            'delivery_city' => 'nullable|string|max:255',
            'delivery_country_id' => 'nullable|exists:countries,id',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
            'pickup_first_name' => 'nullable|string|max:255',
            'pickup_last_name' => 'nullable|string|max:255',
            'pickup_street' => 'nullable|string|max:255',
            'pickup_housenumber' => 'nullable|string|max:255',
            'pickup_zipcode' => 'nullable|string|max:255',
            'pickup_city' => 'nullable|string|max:255',
            'pickup_country_id' => 'nullable|exists:countries,id',
            'return_date' => 'nullable|date',
            'return_time' => 'nullable',
            'return_first_name' => 'nullable|string|max:255',
            'return_last_name' => 'nullable|string|max:255',
            'return_street' => 'nullable|string|max:255',
            'return_housenumber' => 'nullable|string|max:255',
            'return_zipcode' => 'nullable|string|max:255',
            'return_city' => 'nullable|string|max:255',
            'return_country_id' => 'nullable|exists:countries,id',
            'price_delivery' => 'nullable|numeric',
            'price_pickup' => 'nullable|numeric',
            'price_return' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'status_id' => 'nullable|exists:shipment_statuses,id',
            'transaction_done' => 'boolean',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        if ($this->shippingItem?->exists) {
            $this->shippingItem->update($validated);
        } else {
            ShippingItem::create($validated);
        }

        $this->redirect(route('shipping-items.index'), navigate: true);
    }

    public function with(): array
    {
        $selectedAccount = null;
        $availableUsers = collect();

        if ($this->account_id) {
            $selectedAccount = Account::find($this->account_id);
            // Get users that belong to this account via the pivot table
            if ($selectedAccount) {
                $availableUsers = $selectedAccount->users()->orderBy('name')->get();
            }
        }

        return [
            'users' => $availableUsers,
            'accounts' => Account::orderBy('name')->get(),
            'statuses' => ShipmentStatus::where('active', true)->orderBy('list_order')->get(),
            'countries' => Country::where('active', true)->orderBy('name')->get(),
            'selectedAccount' => $selectedAccount,
        ];
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">{{ $shippingItem?->exists ? __('Verzending Bewerken') : __('Nieuwe Verzending') }}</flux:heading>
        <flux:subheading>{{ __('Vul de onderstaande gegevens in') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-8">
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Algemeen') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Account') }}</flux:label>
                    <flux:select wire:model.live="account_id" placeholder="{{ __('Selecteer account') }}">
                        <option value="">{{ __('Selecteer een account') }}</option>
                        @foreach($accounts as $account)
                            <flux:select.option value="{{ $account->id }}">{{ $account->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="account_id" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Gebruiker') }}</flux:label>
                    <flux:select wire:model="user_id" placeholder="{{ __('Selecteer gebruiker') }}" :disabled="!$account_id">
                        <option value="">{{ __('Selecteer een gebruiker') }}</option>
                        @foreach($users as $user)
                            <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="user_id" />
                    @if(!$account_id)
                        <flux:description>{{ __('Selecteer eerst een account') }}</flux:description>
                    @elseif($users->isEmpty())
                        <flux:description class="text-amber-600 dark:text-amber-400">{{ __('Dit account heeft geen gebruikers') }}</flux:description>
                    @endif
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Status') }}</flux:label>
                    <flux:select wire:model="status_id" placeholder="{{ __('Selecteer status') }}">
                        <option value="">{{ __('Selecteer een status') }}</option>
                        @foreach($statuses as $status)
                            <flux:select.option value="{{ $status->id }}">{{ $status->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="status_id" />
                </flux:field>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Bezorgadres') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Voornaam') }}</flux:label>
                    <flux:input wire:model="delivery_first_name" />
                    <flux:error name="delivery_first_name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Achternaam') }}</flux:label>
                    <flux:input wire:model="delivery_last_name" />
                    <flux:error name="delivery_last_name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Straat') }}</flux:label>
                    <flux:input wire:model="delivery_street" />
                    <flux:error name="delivery_street" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Huisnummer') }}</flux:label>
                    <flux:input wire:model="delivery_housenumber" />
                    <flux:error name="delivery_housenumber" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Postcode') }}</flux:label>
                    <flux:input wire:model="delivery_zipcode" />
                    <flux:error name="delivery_zipcode" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Stad') }}</flux:label>
                    <flux:input wire:model="delivery_city" />
                    <flux:error name="delivery_city" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Land') }}</flux:label>
                    <flux:select wire:model="delivery_country_id" placeholder="{{ __('Selecteer land') }}">
                        @foreach($countries as $country)
                            <flux:select.option value="{{ $country->id }}">{{ $country->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="delivery_country_id" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Bezorgdatum') }}</flux:label>
                    <flux:input type="date" wire:model="delivery_date" />
                    <flux:error name="delivery_date" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Bezorgtijd') }}</flux:label>
                    <flux:input type="time" wire:model="delivery_time" />
                    <flux:error name="delivery_time" />
                </flux:field>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Ophaaladres') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Voornaam') }}</flux:label>
                    <flux:input wire:model="pickup_first_name" />
                    <flux:error name="pickup_first_name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Achternaam') }}</flux:label>
                    <flux:input wire:model="pickup_last_name" />
                    <flux:error name="pickup_last_name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Straat') }}</flux:label>
                    <flux:input wire:model="pickup_street" />
                    <flux:error name="pickup_street" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Huisnummer') }}</flux:label>
                    <flux:input wire:model="pickup_housenumber" />
                    <flux:error name="pickup_housenumber" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Postcode') }}</flux:label>
                    <flux:input wire:model="pickup_zipcode" />
                    <flux:error name="pickup_zipcode" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Stad') }}</flux:label>
                    <flux:input wire:model="pickup_city" />
                    <flux:error name="pickup_city" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Land') }}</flux:label>
                    <flux:select wire:model="pickup_country_id" placeholder="{{ __('Selecteer land') }}">
                        @foreach($countries as $country)
                            <flux:select.option value="{{ $country->id }}">{{ $country->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="pickup_country_id" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Ophaaldatum') }}</flux:label>
                    <flux:input type="date" wire:model="pickup_date" />
                    <flux:error name="pickup_date" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Ophaaltijd') }}</flux:label>
                    <flux:input type="time" wire:model="pickup_time" />
                    <flux:error name="pickup_time" />
                </flux:field>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Retouradres') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Voornaam') }}</flux:label>
                    <flux:input wire:model="return_first_name" />
                    <flux:error name="return_first_name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Achternaam') }}</flux:label>
                    <flux:input wire:model="return_last_name" />
                    <flux:error name="return_last_name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Straat') }}</flux:label>
                    <flux:input wire:model="return_street" />
                    <flux:error name="return_street" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Huisnummer') }}</flux:label>
                    <flux:input wire:model="return_housenumber" />
                    <flux:error name="return_housenumber" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Postcode') }}</flux:label>
                    <flux:input wire:model="return_zipcode" />
                    <flux:error name="return_zipcode" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Stad') }}</flux:label>
                    <flux:input wire:model="return_city" />
                    <flux:error name="return_city" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Land') }}</flux:label>
                    <flux:select wire:model="return_country_id" placeholder="{{ __('Selecteer land') }}">
                        @foreach($countries as $country)
                            <flux:select.option value="{{ $country->id }}">{{ $country->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="return_country_id" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Retourdatum') }}</flux:label>
                    <flux:input type="date" wire:model="return_date" />
                    <flux:error name="return_date" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Retourtijd') }}</flux:label>
                    <flux:input type="time" wire:model="return_time" />
                    <flux:error name="return_time" />
                </flux:field>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Prijzen') }}</h3>

            @if($selectedAccount)
                <div class="mb-4 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-600">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Huidig saldo') }} {{ $selectedAccount->name }}</span>
                        <span class="text-lg font-bold {{ $selectedAccount->balance_cents >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            € {{ number_format($selectedAccount->balance_cents / 100, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endif

            @if($this->insufficientBalance)
                <flux:callout variant="danger" class="mb-4">
                    <strong>{{ __('Onvoldoende saldo') }}</strong>
                    <p class="mt-1">{{ __('Het totaalbedrag (€ :total) is hoger dan het beschikbare saldo (€ :balance). De verzending kan niet worden aangemaakt.', [
                        'total' => number_format($total_price, 2, ',', '.'),
                        'balance' => number_format($selectedAccount->balance_cents / 100, 2, ',', '.')
                    ]) }}</p>
                </flux:callout>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Bezorgkosten') }}</flux:label>
                    <flux:input type="number" step="0.01" wire:model.live="price_delivery" />
                    <flux:error name="price_delivery" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Ophaalkosten') }}</flux:label>
                    <flux:input type="number" step="0.01" wire:model.live="price_pickup" />
                    <flux:error name="price_pickup" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Retourkosten') }}</flux:label>
                    <flux:input type="number" step="0.01" wire:model.live="price_return" />
                    <flux:error name="price_return" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Totaal') }}</flux:label>
                    <flux:input type="number" step="0.01" wire:model="total_price" readonly />
                    <flux:error name="total_price" />
                </flux:field>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Transactie') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Transactie ID') }}</flux:label>
                    <flux:input wire:model="transaction_id" />
                    <flux:error name="transaction_id" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Transactie voltooid') }}</flux:label>
                    <flux:checkbox wire:model="transaction_done" />
                    <flux:error name="transaction_done" />
                </flux:field>
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-zinc-200 dark:border-zinc-700 pt-6">
            <flux:button variant="ghost" :href="route('shipping-items.index')" wire:navigate>
                {{ __('Annuleren') }}
            </flux:button>
            <flux:button type="submit" variant="primary" :disabled="$this->insufficientBalance">
                {{ $shippingItem?->exists ? __('Bijwerken') : __('Aanmaken') }}
            </flux:button>
        </div>
    </form>
</div>
