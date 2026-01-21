<?php

use App\Models\SettingsFieldType;
use Livewire\Volt\Component;

new class extends Component {
    public SettingsFieldType $settingsFieldType;
    public string $name = '';
    public int $list_order = 0;
    public bool $active = true;

    public function mount(): void
    {
        $this->name = $this->settingsFieldType->name;
        $this->list_order = $this->settingsFieldType->list_order;
        $this->active = $this->settingsFieldType->active;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'list_order' => ['required', 'integer'],
            'active' => ['boolean'],
        ]);

        $this->settingsFieldType->update($validated);

        $this->redirect(route('settings-field-types.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Edit Field Type') }}</flux:heading>
        <flux:subheading>{{ __('Update field type details') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            :placeholder="__('Field type name')"
            required
        />

        <flux:input
            wire:model="list_order"
            :label="__('List Order')"
            type="number"
            :placeholder="__('0')"
            required
        />

        <flux:checkbox wire:model="active" :label="__('Active')" />

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('settings-field-types.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Update Field Type') }}
            </flux:button>
        </div>
    </form>
</div>
