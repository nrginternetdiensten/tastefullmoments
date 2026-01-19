<?php

use App\Models\{Ticket, TicketChannel, TicketStatus, TicketType};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $typeFilter = '';

    #[Url]
    public string $channelFilter = '';

    public function sortByField(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingChannelFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        $this->dispatch('ticket-deleted');
    }

    public function with(): array
    {
        return [
            'tickets' => Ticket::query()
                ->with(['account', 'user', 'channel', 'status', 'type'])
                ->when($this->statusFilter !== '', fn($query) => $query->where('status_id', $this->statusFilter))
                ->when($this->typeFilter !== '', fn($query) => $query->where('type_id', $this->typeFilter))
                ->when($this->channelFilter !== '', fn($query) => $query->where('channel_id', $this->channelFilter))
                ->when($this->search, function ($query) {
                    $query->where(function ($searchQuery) {
                        $searchQuery
                            ->where('title', 'like', "%{$this->search}%")
                            ->orWhere('content', 'like', "%{$this->search}%")
                            ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                            ->orWhereHas('account', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
                    });
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15),
            'statuses' => TicketStatus::where('active', true)->orderBy('list_order')->get(),
            'types' => TicketType::where('active', true)->orderBy('list_order')->get(),
            'channels' => TicketChannel::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Tickets') }}</flux:heading>
            <flux:subheading>{{ __('Beheer klant support tickets') }}</flux:subheading>
        </div>
        <flux:button :href="route('tickets.create')" wire:navigate variant="primary" icon="plus">
            {{ __('Nieuw ticket') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoek tickets...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <flux:select wire:model.live="statusFilter">
            <option value="">{{ __('Alle statussen') }}</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}">{{ $status->name }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="typeFilter">
            <option value="">{{ __('Alle types') }}</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="channelFilter">
            <option value="">{{ __('Alle kanalen') }}</option>
            @foreach($channels as $channel)
                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
            @endforeach
        </flux:select>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th
                        wire:click="sortByField('id')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('#') }}
                            @if($sortField === 'id')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th
                        wire:click="sortByField('title')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Titel') }}
                            @if($sortField === 'title')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Account') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Gebruiker') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Kanaal') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Status') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Type') }}
                    </th>
                    <th
                        wire:click="sortByField('created_at')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Aangemaakt') }}
                            @if($sortField === 'created_at')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Acties') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse ($tickets as $ticket)
                    <tr wire:key="ticket-{{ $ticket->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                            #{{ $ticket->id }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            <div class="max-w-md truncate">{{ $ticket->title }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $ticket->account?->name ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $ticket->user?->name ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            @if($ticket->channel)
                                <span class="inline-flex items-center rounded px-2 py-1 text-xs font-medium {{ $ticket->channel->colorScheme?->bg_class ?? $ticket->channel->class }} {{ $ticket->channel->colorScheme?->text_class ?? 'text-white' }}">
                                    {{ $ticket->channel->name }}
                                </span>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            @if($ticket->status)
                                <span class="inline-flex items-center rounded px-2 py-1 text-xs font-medium {{ $ticket->status->colorScheme?->bg_class ?? $ticket->status->class }} {{ $ticket->status->colorScheme?->text_class ?? 'text-white' }}">
                                    {{ $ticket->status->name }}
                                </span>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            @if($ticket->type)
                                <span class="inline-flex items-center rounded px-2 py-1 text-xs font-medium {{ $ticket->type->colorScheme?->bg_class ?? $ticket->type->class }} {{ $ticket->type->colorScheme?->text_class ?? 'text-white' }}">
                                    {{ $ticket->type->name }}
                                </span>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $ticket->created_at->format('d-m-Y H:i') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" :href="route('tickets.edit', $ticket)" wire:navigate variant="ghost">
                                    {{ __('Bekijken') }}
                                </flux:button>
                                <flux:modal.trigger :name="'delete-ticket-'.$ticket->id">
                                    <flux:button size="sm" variant="danger">
                                        {{ __('Verwijderen') }}
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('Geen tickets gevonden.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $tickets->links() }}
    </div>

    @foreach ($tickets as $ticket)
        <flux:modal :name="'delete-ticket-'.$ticket->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Ticket verwijderen?') }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __('Je staat op het punt om ticket #') }}{{ $ticket->id }} {{ __('"') }}{{ $ticket->title }}{{ __('" te verwijderen.') }}</p>
                        <p>{{ __('Deze actie kan niet ongedaan worden gemaakt.') }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $ticket->id }})" variant="danger">{{ __('Verwijderen') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
