<?php

use App\Models\{LeadItem, LeadStatus, LeadChannel, LeadCategory};
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $lead_status_id = null;
    public ?int $lead_channel_id = null;
    public ?int $lead_category_id = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingLeadStatusId(): void
    {
        $this->resetPage();
    }

    public function updatingLeadChannelId(): void
    {
        $this->resetPage();
    }

    public function updatingLeadCategoryId(): void
    {
        $this->resetPage();
    }

    public function delete(LeadItem $leadItem): void
    {
        $leadItem->delete();
    }

    public function with(): array
    {
        return [
            'leadItems' => LeadItem::query()
                ->when($this->search, fn($query) =>
                    $query->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")
                        ->orWhere('companyname', 'like', "%{$this->search}%")
                        ->orWhere('emailadres', 'like', "%{$this->search}%")
                )
                ->when($this->lead_status_id, fn($query) =>
                    $query->where('lead_status_id', $this->lead_status_id)
                )
                ->when($this->lead_channel_id, fn($query) =>
                    $query->where('lead_channel_id', $this->lead_channel_id)
                )
                ->when($this->lead_category_id, fn($query) =>
                    $query->where('lead_category_id', $this->lead_category_id)
                )
                ->with(['leadStatus.colorScheme', 'leadChannel.colorScheme', 'leadCategory.colorScheme'])
                ->latest()
                ->paginate(10),
            'leadStatuses' => LeadStatus::where('active', true)->orderBy('list_order')->get(),
            'leadChannels' => LeadChannel::where('active', true)->orderBy('list_order')->get(),
            'leadCategories' => LeadCategory::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Lead Items') }}</flux:heading>
            <flux:subheading>{{ __('Manage lead entries') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('lead-items.create')" wire:navigate variant="primary">
            {{ __('New Lead') }}
        </flux:button>
    </div>

    <div class="space-y-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search leads...') }}"
            icon="magnifying-glass"
        />

        <div class="flex flex-col gap-4 sm:flex-row">
            <flux:select
                wire:model.live="lead_status_id"
                placeholder="{{ __('All statuses') }}"
                class="flex-1"
            >
                <option value="">{{ __('All statuses') }}</option>
                @foreach($leadStatuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="lead_channel_id"
                placeholder="{{ __('All channels') }}"
                class="flex-1"
            >
                <option value="">{{ __('All channels') }}</option>
                @foreach($leadChannels as $channel)
                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="lead_category_id"
                placeholder="{{ __('All categories') }}"
                class="flex-1"
            >
                <option value="">{{ __('All categories') }}</option>
                @foreach($leadCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Lead') }}
                        </th>
                        <th class="hidden px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 md:table-cell lg:px-6">
                            {{ __('Contact') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Classification') }}
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 lg:px-6">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse($leadItems as $item)
                        <tr wire:key="item-{{ $item->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <td class="px-4 py-4 lg:px-6">
                                <div class="space-y-1">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $item->first_name }} {{ $item->last_name }}
                                    </div>
                                    @if($item->companyname)
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $item->companyname }}
                                        </div>
                                    @endif
                                    <div class="text-xs text-zinc-400 dark:text-zinc-500">
                                        {{ $item->created_at->format('d M Y H:i') }}
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400 md:hidden">
                                        @if($item->emailadres)
                                            <div>{{ $item->emailadres }}</div>
                                        @endif
                                        @if($item->phonenumber)
                                            <div>{{ $item->phonenumber }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="hidden whitespace-nowrap px-4 py-4 md:table-cell lg:px-6">
                                <div class="text-sm">
                                    @if($item->emailadres)
                                        <div class="text-zinc-900 dark:text-zinc-100">{{ $item->emailadres }}</div>
                                    @endif
                                    @if($item->phonenumber)
                                        <div class="text-zinc-500 dark:text-zinc-400">{{ $item->phonenumber }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 lg:px-6">
                                <div class="flex flex-wrap gap-1">
                                    @if($item->leadStatus)
                                        @if($item->leadStatus->colorScheme)
                                            <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $item->leadStatus->colorScheme->bg_class }} {{ $item->leadStatus->colorScheme->text_class }}">
                                                {{ $item->leadStatus->name }}
                                            </span>
                                        @else
                                            <flux:badge>{{ $item->leadStatus->name }}</flux:badge>
                                        @endif
                                    @endif
                                    @if($item->leadChannel)
                                        @if($item->leadChannel->colorScheme)
                                            <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $item->leadChannel->colorScheme->bg_class }} {{ $item->leadChannel->colorScheme->text_class }}">
                                                {{ $item->leadChannel->name }}
                                            </span>
                                        @else
                                            <flux:badge>{{ $item->leadChannel->name }}</flux:badge>
                                        @endif
                                    @endif
                                    @if($item->leadCategory)
                                        @if($item->leadCategory->colorScheme)
                                            <span class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $item->leadCategory->colorScheme->bg_class }} {{ $item->leadCategory->colorScheme->text_class }}">
                                                {{ $item->leadCategory->name }}
                                            </span>
                                        @else
                                            <flux:badge>{{ $item->leadCategory->name }}</flux:badge>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right lg:px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button size="sm" :href="route('lead-items.edit', $item)" wire:navigate variant="ghost">
                                        {{ __('Edit') }}
                                    </flux:button>
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this lead?') }}"
                                    >
                                        {{ __('Delete') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <flux:text class="text-zinc-500">{{ __('No leads found.') }}</flux:text>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($leadItems->hasPages())
        <div class="flex justify-center">
            {{ $leadItems->links() }}
        </div>
    @endif
</div>
