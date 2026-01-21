<?php

use App\Models\{SettingsItem, SettingsCategory, SettingsFieldType};
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $title = '';
    public string $value = '';
    public ?int $fieldtype_id = null;
    public ?int $category_id = null;
    public int $list_order = 0;
    public bool $active = true;

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'string'],
            'fieldtype_id' => ['nullable', 'exists:settings_field_types,id'],
            'category_id' => ['nullable', 'exists:settings_categories,id'],
            'list_order' => ['required', 'integer'],
            'active' => ['boolean'],
        ]);

        if (empty($validated['fieldtype_id'])) {
            $validated['fieldtype_id'] = null;
        }
        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
        }

        SettingsItem::create($validated);

        $this->redirect(route('settings-items.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'categories' => SettingsCategory::where('active', true)->orderBy('list_order')->get(),
            'fieldTypes' => SettingsFieldType::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Create Setting') }}</flux:heading>
        <flux:subheading>{{ __('Add a new setting item') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            :placeholder="__('setting_name')"
            required
        />

        <flux:input
            wire:model="title"
            :label="__('Title')"
            type="text"
            :placeholder="__('Setting Title')"
            required
        />

        <flux:textarea
            wire:model="value"
            :label="__('Value')"
            rows="3"
            :placeholder="__('Setting value...')"
        />

        <div class="grid grid-cols-2 gap-4">
            <flux:select
                wire:model="category_id"
                :label="__('Category')"
                :placeholder="__('Select category...')"
            >
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model="fieldtype_id"
                :label="__('Field Type')"
                :placeholder="__('Select field type...')"
            >
                @foreach($fieldTypes as $fieldType)
                    <option value="{{ $fieldType->id }}">{{ $fieldType->name }}</option>
                @endforeach
            </flux:select>
        </div>

        <flux:input
            wire:model="list_order"
            :label="__('List Order')"
            type="number"
            :placeholder="__('0')"
            required
        />

        <flux:checkbox wire:model="active" :label="__('Active')" />

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('settings-items.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Create Setting') }}
            </flux:button>
        </div>
    </form>
</div>
