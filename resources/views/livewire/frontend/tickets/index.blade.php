<?php

use App\Models\{Ticket, TicketStatus};
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?string $statusFilter = null;
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'tickets' => Ticket::query()
                ->where('user_id', auth()->id())
                ->with(['status.colorScheme', 'type'])
                ->when($this->search, fn($query) =>
                    $query->where('title', 'like', "%{$this->search}%")
                        ->orWhere('content', 'like', "%{$this->search}%")
                )
                ->when($this->statusFilter, fn($query) =>
                    $query->where('status_id', $this->statusFilter)
                )
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(10),
            'statuses' => TicketStatus::where('active', true)
                ->orderBy('list_order')
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Mijn Tickets') }}</flux:heading>
            <flux:subheading>{{ __('Overzicht van al je support tickets') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('account.tickets.create')" wire:navigate variant="primary">
            {{ __('Nieuw Ticket') }}
        </flux:button>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoek tickets...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />

        <flux:select wire:model.live="statusFilter" placeholder="{{ __('Alle statussen') }}" class="sm:w-64">
            <flux:select.option value="">{{ __('Alle statussen') }}</flux:select.option>
            @foreach($statuses as $status)
                <flux:select.option value="{{ $status->id }}">{{ $status->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="space-y-4">
        @forelse($tickets as $ticket)
            <a
                href="{{ route('account.tickets.show', $ticket) }}"
                wire:navigate
                wire:key="ticket-{{ $ticket->id }}"
                class="block rounded-lg border border-zinc-200 bg-white p-6 transition-all hover:border-zinc-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-3">
                            <flux:heading size="lg">{{ $ticket->title }}</flux:heading>
                            @if($ticket->status)
                                <flux:badge
                                    :variant="$ticket->status->colorScheme ? 'outline' : 'default'"
                                    class="{{ $ticket->status->colorScheme?->bg_class }} {{ $ticket->status->colorScheme?->text_class }}"
                                >
                                    {{ $ticket->status->name }}
                                </flux:badge>
                            @endif
                            @if($ticket->type)
                                <flux:badge variant="outline">
                                    {{ $ticket->type->name }}
                                </flux:badge>
                            @endif
                        </div>

                        <flux:text class="line-clamp-2 text-zinc-600 dark:text-zinc-400">
                            {{ Str::limit($ticket->content, 150) }}
                        </flux:text>

                        <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <div class="flex items-center gap-1">
                                <flux:icon.calendar class="size-4" />
                                <span>{{ $ticket->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <flux:icon.chat-bubble-left class="size-4" />
                                <span>{{ $ticket->messages()->count() }} {{ __('berichten') }}</span>
                            </div>
                        </div>
                    </div>

                    <flux:icon.chevron-right class="size-5 text-zinc-400" />
                </div>
            </a>
        @empty
            <div class="rounded-lg border border-zinc-200 bg-white p-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
                <flux:icon.ticket class="mx-auto size-12 text-zinc-400" />
                <flux:heading size="lg" class="mt-4">{{ __('Geen tickets gevonden') }}</flux:heading>
                <flux:text class="mt-2 text-zinc-600 dark:text-zinc-400">
                    {{ __('Je hebt nog geen support tickets aangemaakt.') }}
                </flux:text>
                <flux:button
                    :href="route('account.tickets.create')"
                    wire:navigate
                    variant="primary"
                    class="mt-6"
                >
                    {{ __('Maak je eerste ticket aan') }}
                </flux:button>
            </div>
        @endforelse
    </div>

    @if($tickets->hasPages())
        <div>
            {{ $tickets->links() }}
        </div>
    @endif
</div>
