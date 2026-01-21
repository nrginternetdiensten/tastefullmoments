<?php

use App\Models\ContentItem;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(ContentItem $contentItem): void
    {
        $contentItem->delete();
    }

    public function with(): array
    {
        return [
            'contentItems' => ContentItem::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('seo_title', 'like', "%{$this->search}%")
                        ->orWhere('seo_url', 'like', "%{$this->search}%")
                )
                ->with(['folder.colorScheme', 'type.colorScheme'])
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Content Items') }}</flux:heading>
            <flux:subheading>{{ __('Manage content items') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('content-items.create')" wire:navigate variant="primary">
            {{ __('New Item') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search content...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Name') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Folder') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('SEO URL') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse($contentItems as $item)
                        <tr wire:key="item-{{ $item->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <flux:text class="font-medium">{{ $item->name }}</flux:text>
                                    @if($item->seo_title)
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item->seo_title }}</flux:text>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($item->type)
                                    @if($item->type->colorScheme)
                                        <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $item->type->colorScheme->bg_class }} {{ $item->type->colorScheme->text_class }}">
                                            {{ $item->type->name }}
                                        </span>
                                    @else
                                        <flux:text class="text-sm">{{ $item->type->name }}</flux:text>
                                    @endif
                                @else
                                    <flux:text class="text-sm text-zinc-400">-</flux:text>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($item->folder)
                                    @if($item->folder->colorScheme)
                                        <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $item->folder->colorScheme->bg_class }} {{ $item->folder->colorScheme->text_class }}">
                                            {{ $item->folder->name }}
                                        </span>
                                    @else
                                        <flux:text class="text-sm">{{ $item->folder->name }}</flux:text>
                                    @endif
                                @else
                                    <flux:text class="text-sm text-zinc-400">-</flux:text>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->seo_url)
                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item->seo_url }}</flux:text>
                                @else
                                    <flux:text class="text-xs text-zinc-400">-</flux:text>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button size="sm" :href="route('content-items.edit', $item)" wire:navigate variant="ghost">
                                        {{ __('Edit') }}
                                    </flux:button>
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this item?') }}"
                                    >
                                        {{ __('Delete') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('No content items found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $contentItems->links() }}
    </div>
</div>
