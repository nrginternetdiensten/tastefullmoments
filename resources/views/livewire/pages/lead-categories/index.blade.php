<?php

use App\Models\LeadCategory;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(LeadCategory $leadCategory): void
    {
        $leadCategory->delete();
    }

    public function with(): array
    {
        return [
            'leadCategories' => LeadCategory::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                )
                ->with('colorScheme')
                ->orderBy('list_order')
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Lead Categories') }}</flux:heading>
            <flux:subheading>{{ __('Manage lead category options') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('lead-categories.create')" wire:navigate variant="primary">
            {{ __('New Category') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search categories...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Name') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Status') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Order') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($leadCategories as $category)
                    <tr wire:key="category-{{ $category->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($category->colorScheme)
                                <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $category->colorScheme->bg_class }} {{ $category->colorScheme->text_class }}">
                                    {{ $category->name }}
                                </span>
                            @else
                                <flux:text class="font-medium">{{ $category->name }}</flux:text>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:badge :variant="$category->active ? 'success' : 'ghost'">
                                {{ $category->active ? __('Active') : __('Inactive') }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text>{{ $category->list_order }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" :href="route('lead-categories.edit', $category)" wire:navigate variant="ghost">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    wire:click="delete({{ $category->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this category?') }}"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('No categories found.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leadCategories->hasPages())
        <div class="flex justify-center">
            {{ $leadCategories->links() }}
        </div>
    @endif
</div>
