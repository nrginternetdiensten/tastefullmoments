<?php

use App\Models\{Ticket, TicketMessage};
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    public Ticket $ticket;
    public string $newMessage = '';
    public array $attachments = [];

    public function mount(Ticket $ticket): void
    {
        // Ensure the user can only view their own tickets
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $this->ticket = $ticket->load(['status.colorScheme', 'type', 'channel', 'messages.user', 'attachments.user']);
    }

    public function addMessage(): void
    {
        $validated = $this->validate([
            'newMessage' => ['required', 'string', 'min:3'],
            'attachments.*' => ['file', 'max:10240'],
        ]);

        $this->ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validated['newMessage'],
            'is_internal' => false,
        ]);

        // Handle file uploads
        foreach ($this->attachments as $attachment) {
            $filename = $attachment->hashName();
            $path = $attachment->storeAs('ticket-attachments', $filename, 'public');

            $this->ticket->attachments()->create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_filename' => $attachment->getClientOriginalName(),
                'mime_type' => $attachment->getMimeType(),
                'size' => $attachment->getSize(),
                'path' => $path,
            ]);
        }

        $this->newMessage = '';
        $this->attachments = [];

        $this->ticket->refresh();
        $this->ticket->load(['messages.user', 'attachments.user']);

        $this->dispatch('message-added');
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center gap-4">
        <flux:button
            icon="arrow-left"
            :href="route('account.tickets.index')"
            wire:navigate
            variant="ghost"
        >
            {{ __('Terug naar overzicht') }}
        </flux:button>
    </div>

    <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 space-y-3">
                    <flux:heading size="xl">{{ $ticket->title }}</flux:heading>

                    <div class="flex flex-wrap items-center gap-3">
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
                        @if($ticket->channel)
                            <flux:badge variant="outline">
                                <flux:icon.chat-bubble-left class="size-3" />
                                {{ $ticket->channel->name }}
                            </flux:badge>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                        <div class="flex items-center gap-1">
                            <flux:icon.calendar class="size-4" />
                            <span>{{ __('Aangemaakt op') }} {{ $ticket->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <flux:icon.clock class="size-4" />
                            <span>{{ __('Laatst gewijzigd') }} {{ $ticket->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <flux:separator />

            <div class="prose prose-zinc max-w-none dark:prose-invert">
                <p>{{ $ticket->content }}</p>
            </div>

            @if($ticket->attachments->count() > 0)
                <flux:separator />

                <div class="space-y-2">
                    <flux:subheading>{{ __('Bijlagen') }}</flux:subheading>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach($ticket->attachments as $attachment)
                            <a
                                href="{{ Storage::url($attachment->path) }}"
                                target="_blank"
                                class="flex items-center gap-2 rounded-md border border-zinc-200 bg-zinc-50 p-3 transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700"
                            >
                                <flux:icon.paper-clip class="size-4 text-zinc-500" />
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $attachment->original_filename }}
                                    </div>
                                    <div class="text-xs text-zinc-500">
                                        {{ number_format($attachment->size / 1024, 2) }} KB
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Messages -->
    <div class="space-y-4">
        <flux:heading size="lg">{{ __('Berichten') }}</flux:heading>

        @forelse($ticket->messages as $message)
            <div
                wire:key="message-{{ $message->id }}"
                class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900 {{ $message->is_internal ? 'border-l-4 border-l-orange-500' : '' }}"
            >
                <div class="flex items-start gap-3">
                    <div class="flex size-10 flex-shrink-0 items-center justify-center rounded-full bg-zinc-200 font-semibold text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">
                        {{ $message->user->initials() }}
                    </div>

                    <div class="flex-1 space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <flux:text class="font-semibold">{{ $message->user->name }}</flux:text>
                                @if($message->is_internal)
                                    <flux:badge variant="warning" size="sm">{{ __('Intern') }}</flux:badge>
                                @endif
                            </div>
                            <flux:text class="text-sm text-zinc-500">
                                {{ $message->created_at->format('d M Y H:i') }}
                            </flux:text>
                        </div>

                        <div class="prose prose-zinc max-w-none text-zinc-700 dark:prose-invert dark:text-zinc-300">
                            <p>{{ $message->message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-8 text-center dark:border-zinc-700 dark:bg-zinc-800">
                <flux:text class="text-zinc-500">{{ __('Nog geen berichten in dit ticket.') }}</flux:text>
            </div>
        @endforelse
    </div>

    <!-- Add Message Form -->
    <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="lg" class="mb-4">{{ __('Bericht toevoegen') }}</flux:heading>

        <form wire:submit="addMessage" class="space-y-4">
            <flux:field>
                <flux:label>{{ __('Bericht') }}</flux:label>
                <flux:textarea
                    wire:model="newMessage"
                    rows="4"
                    placeholder="{{ __('Typ hier je bericht...') }}"
                    required
                />
                <flux:error name="newMessage" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Bijlagen') }}</flux:label>
                <flux:input type="file" wire:model="attachments" multiple />
                <flux:description>{{ __('Maximaal 10MB per bestand') }}</flux:description>
                <flux:error name="attachments.*" />

                @if($attachments)
                    <div class="mt-2 space-y-1">
                        @foreach($attachments as $attachment)
                            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $attachment->getClientOriginalName() }} ({{ number_format($attachment->getSize() / 1024, 2) }} KB)
                            </div>
                        @endforeach
                    </div>
                @endif
            </flux:field>

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" icon="paper-airplane">
                    {{ __('Verstuur bericht') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
