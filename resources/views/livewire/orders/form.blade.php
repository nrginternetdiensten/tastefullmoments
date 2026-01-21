<?php

use App\Models\Account;
use App\Models\InvoiceTax;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Livewire\Volt\Component;

new class extends Component
{
    public ?Order $order = null;

    public string $order_id = '';

    public ?string $account_id = null;

    public ?string $user_id = null;

    public ?string $status_id = null;

    public string $accountSearch = '';

    public string $userSearch = '';

    public array $lines = [];

    public function mount(?Order $order = null): void
    {
        if ($order && $order->exists) {
            $this->order = $order->load('lines.tax');
            $this->order_id = $order->order_id;
            $this->account_id = $order->account_id ? (string) $order->account_id : null;
            $this->user_id = $order->user_id ? (string) $order->user_id : null;
            $this->status_id = $order->status_id ? (string) $order->status_id : null;

            // Load existing lines
            $this->lines = $order->lines->map(fn ($line) => [
                'id' => $line->id,
                'name' => $line->name,
                'quantity' => $line->quantity,
                'price_exc' => $line->price_exc,
                'tax_id' => $line->tax_id ? (string) $line->tax_id : null,
            ])->toArray();
        }

        // Always have at least one empty line
        if (empty($this->lines)) {
            $this->addLine();
        }
    }

    public function addLine(): void
    {
        $this->lines[] = [
            'id' => null,
            'name' => '',
            'quantity' => 1,
            'price_exc' => '',
            'tax_id' => null,
        ];
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);

        // Keep at least one line
        if (empty($this->lines)) {
            $this->addLine();
        }
    }

    public function updatedAccountId(): void
    {
        $this->user_id = null;
    }

    public function save(): void
    {
        $this->authorize($this->order ? 'orders.edit' : 'orders.create');

        $validated = $this->validate([
            'order_id' => ['required', 'string', 'max:255', $this->order ? 'unique:orders,order_id,'.$this->order->id : 'unique:orders,order_id'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'status_id' => ['nullable', 'exists:order_statuses,id'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.name' => ['required', 'string', 'max:255'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.price_exc' => ['required', 'numeric', 'min:0'],
            'lines.*.tax_id' => ['required', 'exists:invoice_taxes,id'],
        ]);

        // Calculate totals
        $totalExc = 0;
        $totalTax = 0;

        foreach ($validated['lines'] as $line) {
            $tax = InvoiceTax::find($line['tax_id']);
            $taxRate = $tax->percentage / 100;

            $lineExc = $line['price_exc'] * $line['quantity'];
            $lineTax = $lineExc * $taxRate;

            $totalExc += $lineExc;
            $totalTax += $lineTax;
        }

        $orderData = [
            'order_id' => $validated['order_id'],
            'account_id' => $validated['account_id'],
            'user_id' => $validated['user_id'],
            'status_id' => $validated['status_id'],
            'total_exc' => $totalExc,
            'total_tax' => $totalTax,
            'total' => $totalExc + $totalTax,
        ];

        if ($this->order) {
            $this->order->update($orderData);
            $this->order->refresh();

            // Delete removed lines
            $keptLineIds = collect($validated['lines'])->pluck('id')->filter();
            $this->order->lines()->whereNotIn('id', $keptLineIds)->delete();
        } else {
            $this->order = Order::create($orderData);
        }

        // Save lines
        foreach ($validated['lines'] as $lineData) {
            $tax = InvoiceTax::find($lineData['tax_id']);
            $taxRate = $tax->percentage / 100;

            $priceExc = $lineData['price_exc'];
            $priceTax = $priceExc * $taxRate;
            $price = $priceExc + $priceTax;

            $quantity = $lineData['quantity'];
            $totalExc = $priceExc * $quantity;
            $totalTax = $totalExc * $taxRate;
            $total = $totalExc + $totalTax;

            $lineSaveData = [
                'name' => $lineData['name'],
                'quantity' => $quantity,
                'price' => $price,
                'price_tax' => $priceTax,
                'price_exc' => $priceExc,
                'total' => $total,
                'total_exc' => $totalExc,
                'total_tax' => $totalTax,
                'tax_id' => $lineData['tax_id'],
            ];

            if (! empty($lineData['id'])) {
                $this->order->lines()->where('id', $lineData['id'])->update($lineSaveData);
            } else {
                $this->order->lines()->create($lineSaveData);
            }
        }

        $this->dispatch('order-saved');
        $this->redirect(route('orders.index'), navigate: true);
    }

    public function with(): array
    {
        $totalExc = 0;
        $totalTax = 0;

        foreach ($this->lines as $line) {
            if (! empty($line['price_exc']) && ! empty($line['quantity']) && ! empty($line['tax_id'])) {
                $tax = InvoiceTax::find($line['tax_id']);
                if ($tax) {
                    $lineExc = $line['price_exc'] * $line['quantity'];
                    $lineTax = $lineExc * ($tax->percentage / 100);
                    $totalExc += $lineExc;
                    $totalTax += $lineTax;
                }
            }
        }

        return [
            'accounts' => Account::query()
                ->when($this->accountSearch, fn ($q) => $q->where('name', 'like', "%{$this->accountSearch}%"))
                ->orderBy('name')
                ->limit(10)
                ->get(),
            'users' => User::query()
                ->when($this->account_id, fn ($q) => $q->whereHas('accounts', fn ($q) => $q->where('accounts.id', $this->account_id)))
                ->when($this->userSearch, fn ($q) => $q->where('name', 'like', "%{$this->userSearch}%"))
                ->orderBy('name')
                ->limit(10)
                ->get(),
            'statuses' => OrderStatus::query()
                ->where('active', true)
                ->with('colorScheme')
                ->orderBy('list_order')
                ->get(),
            'taxes' => InvoiceTax::query()
                ->where('active', true)
                ->orderBy('name')
                ->get(),
            'calculatedTotalExc' => $totalExc,
            'calculatedTotalTax' => $totalTax,
            'calculatedTotal' => $totalExc + $totalTax,
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $order ? __('Order bewerken') : __('Nieuwe order') }}</flux:heading>
            <flux:subheading>{{ $order ? __('Bewerk een bestaande order') : __('Maak een nieuwe order aan') }}</flux:subheading>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <flux:field>
            <flux:label>{{ __('Ordernummer') }}</flux:label>
            <flux:input wire:model="order_id" placeholder="{{ __('ORD-12345') }}" />
            <flux:error name="order_id" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Account') }}</flux:label>
            <flux:select wire:model.live="account_id" variant="combobox" placeholder="{{ __('Selecteer account') }}" :filter="false">
                <x-slot name="input">
                    <flux:select.input wire:model.live.debounce.300ms="accountSearch" placeholder="{{ __('Zoek account...') }}" />
                </x-slot>
                @foreach($accounts as $account)
                    <flux:select.option value="{{ $account->id }}" wire:key="account-{{ $account->id }}">{{ $account->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="account_id" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Gebruiker') }}</flux:label>
            <flux:select
                wire:model.live="user_id"
                variant="combobox"
                placeholder="{{ __('Selecteer gebruiker') }}"
                :filter="false"
                :disabled="!$account_id"
            >
                <x-slot name="input">
                    <flux:select.input wire:model.live.debounce.300ms="userSearch" placeholder="{{ __('Zoek gebruiker...') }}" :disabled="!$account_id" />
                </x-slot>
                @if($account_id)
                    @foreach($users as $user)
                        <flux:select.option value="{{ $user->id }}" wire:key="user-{{ $user->id }}">
                            {{ $user->name }} <span class="text-xs text-zinc-500">({{ $user->email }})</span>
                        </flux:select.option>
                    @endforeach
                @else
                    <flux:select.option disabled>{{ __('Selecteer eerst een account') }}</flux:select.option>
                @endif
            </flux:select>
            <flux:error name="user_id" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Status') }}</flux:label>
            <flux:select wire:model.live="status_id" placeholder="{{ __('Selecteer een status') }}">
                <option value="0">{{ __('Geen status') }}</option>
                @foreach ($statuses as $status)
                    <flux:select.option wire:key="status-{{ $status->id }}" value="{{ $status->id }}">
                        {{ $status->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="status_id" />
        </flux:field>

        {{-- Order Lines --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <flux:heading size="lg">{{ __('Orderregels') }}</flux:heading>
                <flux:button size="sm" wire:click="addLine" variant="ghost" icon="plus">
                    {{ __('Regel toevoegen') }}
                </flux:button>
            </div>

            <div class="space-y-3">
                @foreach($lines as $index => $line)
                    <div wire:key="line-{{ $index }}" class="grid grid-cols-12 gap-3 rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                        <div class="col-span-5">
                            <flux:input
                                wire:model="lines.{{ $index }}.name"
                                placeholder="{{ __('Omschrijving') }}"
                            />
                            <flux:error name="lines.{{ $index }}.name" />
                        </div>

                        <div class="col-span-2">
                            <flux:input
                                wire:model.live="lines.{{ $index }}.quantity"
                                type="number"
                                min="1"
                                placeholder="{{ __('Aantal') }}"
                            />
                            <flux:error name="lines.{{ $index }}.quantity" />
                        </div>

                        <div class="col-span-2">
                            <flux:input
                                wire:model.live="lines.{{ $index }}.price_exc"
                                type="number"
                                step="0.01"
                                placeholder="{{ __('Prijs excl.') }}"
                            />
                            <flux:error name="lines.{{ $index }}.price_exc" />
                        </div>

                        <div class="col-span-2">
                            <flux:select wire:model.live="lines.{{ $index }}.tax_id" placeholder="{{ __('BTW') }}">
                                @foreach($taxes as $tax)
                                    <flux:select.option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->percentage }}%)</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="lines.{{ $index }}.tax_id" />
                        </div>

                        <div class="col-span-1 flex items-start justify-end">
                            <flux:button
                                size="sm"
                                wire:click="removeLine({{ $index }})"
                                variant="danger"
                                icon="trash"
                            />
                        </div>

                        @if(!empty($line['price_exc']) && !empty($line['quantity']) && !empty($line['tax_id']))
                            @php
                                $tax = $taxes->firstWhere('id', $line['tax_id']);
                                if ($tax) {
                                    $lineExc = $line['price_exc'] * $line['quantity'];
                                    $lineTax = $lineExc * ($tax->percentage / 100);
                                    $lineTotal = $lineExc + $lineTax;
                                }
                            @endphp
                            @if(isset($lineTotal))
                                <div class="col-span-12 text-right text-sm text-zinc-600 dark:text-zinc-400">
                                    Subtotaal: € {{ number_format($lineExc, 2, ',', '.') }} + BTW € {{ number_format($lineTax, 2, ',', '.') }} = <strong>€ {{ number_format($lineTotal, 2, ',', '.') }}</strong>
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="space-y-2 text-right">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600 dark:text-zinc-400">{{ __('Totaal excl. BTW:') }}</span>
                        <span class="font-mono">€ {{ number_format($calculatedTotalExc, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600 dark:text-zinc-400">{{ __('BTW:') }}</span>
                        <span class="font-mono">€ {{ number_format($calculatedTotalTax, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t border-zinc-200 pt-2 text-lg font-semibold dark:border-zinc-700">
                        <span>{{ __('Totaal incl. BTW:') }}</span>
                        <span class="font-mono">€ {{ number_format($calculatedTotal, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">
                {{ $order ? __('Opslaan') : __('Aanmaken') }}
            </flux:button>
            <flux:button type="button" :href="route('orders.index')" wire:navigate variant="ghost">
                {{ __('Annuleren') }}
            </flux:button>
        </div>
    </form>
</div>
