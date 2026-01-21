<?php

use App\Models\LeadStatus;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(LeadStatus $leadStatus): void
    {
        $leadStatus->delete();
    }

    public function with(): array
    {
        return [
            'leadStatuses' => LeadStatus::query()
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
            <flux:heading size="xl">{{ __('Lead Statuses') }}</flux:heading>
            <flux:subheading>{{ __('Manage lead status options') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('lead-statuses.create')" wire:navigate variant="primary">
            {{ __('New Status') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search statuses...') }}"
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
                @forelse($leadStatuses as $status)
                    <tr wire:key="status-{{ $status->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($status->colorScheme)
                                <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $status->colorScheme->bg_class }} {{ $status->colorScheme->text_class }}">
                                    {{ $status->name }}
                                </span>
                            @else
                                <flux:text class="font-medium">{{ $status->name }}</flux:text>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:badge :variant="$status->active ? 'success' : 'ghost'">
                                {{ $status->active ? __('Active') : __('Inactive') }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text>{{ $status->list_order }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" :href="route('lead-statuses.edit', $status)" wire:navigate variant="ghost">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    wire:click="delete({{ $status->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this status?') }}"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('No statuses found.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leadStatuses->hasPages())
        <div class="flex justify-center">
            {{ $leadStatuses->links() }}
        </div>
    @endif
</div>
