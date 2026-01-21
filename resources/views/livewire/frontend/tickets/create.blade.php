<?php

use App\Models\TicketType;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    public string $title = '';
    public string $content = '';
    public ?string $type_id = null;
    public array $attachments = [];

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'type_id' => ['nullable', 'exists:ticket_types,id'],
            'attachments.*' => ['file', 'max:10240'],
        ]);

        // Get the user's first account (you may want to adjust this logic)
        $account = auth()->user()->accounts()->first();

        if (!$account) {
            $this->addError('account', __('Je moet gekoppeld zijn aan een account om een ticket aan te maken.'));
            return;
        }

        $ticket = $account->tickets()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'type_id' => $validated['type_id'],
            'channel_id' => 4, // Default channel ID
            // Set default status to first active status
            'status_id' => \App\Models\TicketStatus::where('active', true)->orderBy('list_order')->first()?->id,
        ]);

        // Handle file uploads
        foreach ($this->attachments as $attachment) {
            $filename = $attachment->hashName();
            $path = $attachment->storeAs('ticket-attachments', $filename, 'public');

            $ticket->attachments()->create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_filename' => $attachment->getClientOriginalName(),
                'mime_type' => $attachment->getMimeType(),
                'size' => $attachment->getSize(),
                'path' => $path,
            ]);
        }

        $this->redirect(route('account.tickets.show', $ticket), navigate: true);
    }

    public function with(): array
    {
        return [
            'types' => TicketType::where('active', true)->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
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

    <div>
        <flux:heading size="xl">{{ __('Nieuw Support Ticket') }}</flux:heading>
        <flux:subheading>{{ __('Omschrijf je probleem of vraag') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:field>
            <flux:label>{{ __('Onderwerp') }}</flux:label>
            <flux:input
                wire:model="title"
                type="text"
                required
                autofocus
                placeholder="{{ __('Wat is het probleem of de vraag?') }}"
            />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Type') }}</flux:label>
            <flux:select wire:model="type_id" placeholder="{{ __('Selecteer een type') }}">
                @foreach($types as $type)
                    <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="type_id" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Omschrijving') }}</flux:label>
            <flux:textarea
                wire:model="content"
                rows="8"
                required
                placeholder="{{ __('Beschrijf je probleem of vraag zo gedetailleerd mogelijk...') }}"
            />
            <flux:description>{{ __('Minimaal 10 karakters') }}</flux:description>
            <flux:error name="content" />
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

        <flux:error name="account" />

        <div class="flex items-center justify-between gap-4">
            <flux:button
                type="button"
                variant="ghost"
                :href="route('account.tickets.index')"
                wire:navigate
            >
                {{ __('Annuleren') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Ticket aanmaken') }}
            </flux:button>
        </div>
    </form>
</div>
