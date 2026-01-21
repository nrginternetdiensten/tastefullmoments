<?php

use App\Models\{SettingsItem, SettingsCategory, SettingsFieldType};
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $category_id = null;
    public ?int $fieldtype_id = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingFieldtypeId(): void
    {
        $this->resetPage();
    }

    public function delete(SettingsItem $settingsItem): void
    {
        $settingsItem->delete();
    }

    public function with(): array
    {
        return [
            'settingsItems' => SettingsItem::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('title', 'like', "%{$this->search}%")
                )
                ->when($this->category_id, fn($query) =>
                    $query->where('category_id', $this->category_id)
                )
                ->when($this->fieldtype_id, fn($query) =>
                    $query->where('fieldtype_id', $this->fieldtype_id)
                )
                ->with(['category', 'fieldType'])
                ->orderBy('list_order')
                ->paginate(10),
            'categories' => SettingsCategory::where('active', true)->orderBy('list_order')->get(),
            'fieldTypes' => SettingsFieldType::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Settings Items') }}</flux:heading>
            <flux:subheading>{{ __('Manage settings entries') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('settings-items.create')" wire:navigate variant="primary">
            {{ __('New Setting') }}
        </flux:button>
    </div>

    <div class="space-y-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search settings...') }}"
            icon="magnifying-glass"
        />

        <div class="flex flex-col gap-4 sm:flex-row">
            <flux:select
                wire:model.live="category_id"
                placeholder="{{ __('All categories') }}"
                class="flex-1"
            >
                <option value="">{{ __('All categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="fieldtype_id"
                placeholder="{{ __('All field types') }}"
                class="flex-1"
            >
                <option value="">{{ __('All field types') }}</option>
                @foreach($fieldTypes as $fieldType)
                    <option value="{{ $fieldType->id }}">{{ $fieldType->name }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Setting') }}
                        </th>
                        <th class="hidden px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 md:table-cell">
                            {{ __('Category') }}
                        </th>
                        <th class="hidden px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:table-cell">
                            {{ __('Type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse($settingsItems as $item)
                        <tr wire:key="item-{{ $item->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $item->title }}</div>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $item->name }}</div>
                                    @if($item->value)
                                        <div class="text-xs text-zinc-400 dark:text-zinc-500">{{ Str::limit($item->value, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="hidden whitespace-nowrap px-6 py-4 md:table-cell">
                                @if($item->category)
                                    <flux:badge>{{ $item->category->name }}</flux:badge>
                                @endif
                            </td>
                            <td class="hidden whitespace-nowrap px-6 py-4 lg:table-cell">
                                @if($item->fieldType)
                                    <flux:badge color="zinc">{{ $item->fieldType->name }}</flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($item->active)
                                    <flux:badge color="green">{{ __('Active') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc">{{ __('Inactive') }}</flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button size="sm" :href="route('settings-items.edit', $item)" wire:navigate variant="ghost">
                                        {{ __('Edit') }}
                                    </flux:button>
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this setting?') }}"
                                    >
                                        {{ __('Delete') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <flux:text class="text-zinc-500">{{ __('No settings found.') }}</flux:text>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($settingsItems->hasPages())
        <div class="flex justify-center">
            {{ $settingsItems->links() }}
        </div>
    @endif
</div>
