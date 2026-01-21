<?php

use App\Models\SettingsFieldType;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(SettingsFieldType $settingsFieldType): void
    {
        $settingsFieldType->delete();
    }

    public function with(): array
    {
        return [
            'settingsFieldTypes' => SettingsFieldType::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                )
                ->orderBy('list_order')
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Settings Field Types') }}</flux:heading>
            <flux:subheading>{{ __('Manage field type entries') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('settings-field-types.create')" wire:navigate variant="primary">
            {{ __('New Field Type') }}
        </flux:button>
    </div>

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="{{ __('Search field types...') }}"
        icon="magnifying-glass"
    />

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Name') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Order') }}
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
                    @forelse($settingsFieldTypes as $fieldType)
                        <tr wire:key="fieldType-{{ $fieldType->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <td class="whitespace-nowrap px-6 py-4">
                                <flux:text class="font-medium">{{ $fieldType->name }}</flux:text>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <flux:text>{{ $fieldType->list_order }}</flux:text>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($fieldType->active)
                                    <flux:badge color="green">{{ __('Active') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc">{{ __('Inactive') }}</flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button size="sm" :href="route('settings-field-types.edit', $fieldType)" wire:navigate variant="ghost">
                                        {{ __('Edit') }}
                                    </flux:button>
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        wire:click="delete({{ $fieldType->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this field type?') }}"
                                    >
                                        {{ __('Delete') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <flux:text class="text-zinc-500">{{ __('No field types found.') }}</flux:text>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($settingsFieldTypes->hasPages())
        <div class="flex justify-center">
            {{ $settingsFieldTypes->links() }}
        </div>
    @endif
</div>
