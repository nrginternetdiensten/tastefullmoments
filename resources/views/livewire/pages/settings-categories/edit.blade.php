<?php

use App\Models\SettingsCategory;
use Livewire\Volt\Component;

new class extends Component {
    public SettingsCategory $settingsCategory;
    public string $name = '';
    public int $list_order = 0;
    public bool $active = true;

    public function mount(): void
    {
        $this->name = $this->settingsCategory->name;
        $this->list_order = $this->settingsCategory->list_order;
        $this->active = $this->settingsCategory->active;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'list_order' => ['required', 'integer'],
            'active' => ['boolean'],
        ]);

        $this->settingsCategory->update($validated);

        $this->redirect(route('settings-categories.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Edit Settings Category') }}</flux:heading>
        <flux:subheading>{{ __('Update category details') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            :placeholder="__('Category name')"
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
            <flux:button variant="ghost" :href="route('settings-categories.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Update Category') }}
            </flux:button>
        </div>
    </form>
</div>
