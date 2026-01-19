<?php

use App\Models\InvoiceTax;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?InvoiceTax $invoiceTax = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $percentage = '';

    #[Modelable]
    public bool $active = true;

    public function mount(): void
    {
        if ($this->invoiceTax) {
            $this->name = $this->invoiceTax->name;
            $this->percentage = (string) $this->invoiceTax->percentage;
            $this->active = $this->invoiceTax->active;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'active' => ['boolean'],
        ]);

        if ($this->invoiceTax) {
            $this->invoiceTax->update($validated);
            $message = 'Invoice tax updated successfully.';
        } else {
            InvoiceTax::create($validated);
            $message = 'Invoice tax created successfully.';
        }

        $this->dispatch('invoice-tax-saved');

        $this->redirect(route('invoice-taxes.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $invoiceTax ? __('Edit Invoice Tax') : __('Create Invoice Tax') }}</flux:heading>
        <flux:subheading>{{ $invoiceTax ? __('Update tax rate details') : __('Add a new tax rate') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            :placeholder="__('VAT, GST, Sales Tax...')"
        />

        <flux:input
            wire:model="percentage"
            :label="__('Percentage')"
            type="number"
            step="0.01"
            min="0"
            max="100"
            required
            :placeholder="__('21.00')"
        />

        <flux:checkbox wire:model="active" :label="__('Active')" />

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('invoice-taxes.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $invoiceTax ? __('Update Tax') : __('Create Tax') }}
            </flux:button>
        </div>
    </form>
</div>
