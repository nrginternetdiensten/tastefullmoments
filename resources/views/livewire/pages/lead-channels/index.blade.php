<?php

use App\Models\LeadChannel;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(LeadChannel $leadChannel): void
    {
        $leadChannel->delete();
    }

    public function with(): array
    {
        return [
            'leadChannels' => LeadChannel::query()
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
            <flux:heading size="xl">{{ __('Lead Channels') }}</flux:heading>
            <flux:subheading>{{ __('Manage lead channel options') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('lead-channels.create')" wire:navigate variant="primary">
            {{ __('New Channel') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search channels...') }}"
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
                @forelse($leadChannels as $channel)
                    <tr wire:key="channel-{{ $channel->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($channel->colorScheme)
                                <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $channel->colorScheme->bg_class }} {{ $channel->colorScheme->text_class }}">
                                    {{ $channel->name }}
                                </span>
                            @else
                                <flux:text class="font-medium">{{ $channel->name }}</flux:text>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:badge :variant="$channel->active ? 'success' : 'ghost'">
                                {{ $channel->active ? __('Active') : __('Inactive') }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text>{{ $channel->list_order }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" :href="route('lead-channels.edit', $channel)" wire:navigate variant="ghost">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    wire:click="delete({{ $channel->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this channel?') }}"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('No channels found.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leadChannels->hasPages())
        <div class="flex justify-center">
            {{ $leadChannels->links() }}
        </div>
    @endif
</div>
