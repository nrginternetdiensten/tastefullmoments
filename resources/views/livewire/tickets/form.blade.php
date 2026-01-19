<?php

use App\Models\{Account, Ticket, TicketChannel, TicketStatus, TicketType, User};
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ?Ticket $ticket = null;
    public string $title = '';
    public string $content = '';
    public ?string $account_id = null;
    public ?string $user_id = null;
    public ?string $channel_id = null;
    public ?string $status_id = null;
    public ?string $type_id = null;
    public string $newMessage = '';
    public ?int $editingMessageId = null;
    public string $editingMessageContent = '';
    public $attachments = [];
    
    public string $accountSearch = '';
    public string $userSearch = '';

    public function mount(?Ticket $ticket = null): void
    {
        if ($ticket && $ticket->exists) {
            $this->ticket = $ticket;
            $this->title = $ticket->title;
            $this->content = $ticket->content;
            $this->account_id = $ticket->account_id ? (string) $ticket->account_id : null;
            $this->user_id = $ticket->user_id ? (string) $ticket->user_id : null;
            $this->channel_id = $ticket->channel_id ? (string) $ticket->channel_id : null;
            $this->status_id = $ticket->status_id ? (string) $ticket->status_id : null;
            $this->type_id = $ticket->type_id ? (string) $ticket->type_id : null;
        }
    }
    
    public function updatedAccountId(): void
    {
        // Reset user_id when account changes
        $this->user_id = null;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'channel_id' => ['nullable', 'exists:ticket_channels,id'],
            'status_id' => ['nullable', 'exists:ticket_statuses,id'],
            'type_id' => ['nullable', 'exists:ticket_types,id'],
        ]);

        if ($this->ticket) {
            $this->ticket->update($validated);
        } else {
            Ticket::create($validated);
        }

        $this->dispatch('ticket-saved');
        $this->redirect(route('tickets.index'), navigate: true);
    }

    public function addMessage(): void
    {
        if (! $this->ticket) {
            return;
        }

        $validated = $this->validate([
            'newMessage' => ['required', 'string'],
        ], [], [
            'newMessage' => __('reactie'),
        ]);

        $this->ticket->messages()->create([
            'message' => $validated['newMessage'],
            'user_id' => auth()->id(),
        ]);

        $this->newMessage = '';
        $this->ticket->refresh();
    }

    public function editMessage(int $messageId): void
    {
        $message = $this->ticket->messages()->findOrFail($messageId);
        
        if ($message->user_id !== auth()->id()) {
            return;
        }

        $this->editingMessageId = $messageId;
        $this->editingMessageContent = $message->message;
    }

    public function updateMessage(): void
    {
        if (! $this->editingMessageId) {
            return;
        }

        $message = $this->ticket->messages()->findOrFail($this->editingMessageId);
        
        if ($message->user_id !== auth()->id()) {
            return;
        }

        $validated = $this->validate([
            'editingMessageContent' => ['required', 'string'],
        ], [], [
            'editingMessageContent' => __('reactie'),
        ]);

        $message->update([
            'message' => $validated['editingMessageContent'],
        ]);

        $this->cancelEdit();
        $this->ticket->refresh();
    }

    public function cancelEdit(): void
    {
        $this->editingMessageId = null;
        $this->editingMessageContent = '';
    }

    public function deleteMessage(int $messageId): void
    {
        $message = $this->ticket->messages()->findOrFail($messageId);
        
        if ($message->user_id !== auth()->id()) {
            return;
        }

        $message->delete();
        $this->ticket->refresh();
    }

    public function uploadAttachments(): void
    {
        if (! $this->ticket) {
            return;
        }

        $validated = $this->validate([
            'attachments.*' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,pdf', 'max:10240'],
        ], [
            'attachments.*.mimes' => __('Alleen afbeeldingen (JPG, PNG, GIF, WebP) en PDF bestanden zijn toegestaan.'),
            'attachments.*.max' => __('Het bestand mag maximaal 10MB zijn.'),
        ]);

        foreach ($this->attachments as $file) {
            $filename = $file->hashName();
            $path = $file->store('ticket-attachments', 'public');

            $this->ticket->attachments()->create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
            ]);
        }

        $this->attachments = [];
        $this->ticket->refresh();
    }

    public function deleteAttachment(int $attachmentId): void
    {
        $attachment = $this->ticket->attachments()->findOrFail($attachmentId);
        
        if ($attachment->user_id !== auth()->id()) {
            return;
        }

        \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->path);
        $attachment->delete();
        $this->ticket->refresh();
    }

    public function with(): array
    {
        $accountsQuery = Account::query()->orderBy('name');
        if ($this->accountSearch) {
            $accountsQuery->where('name', 'like', '%' . $this->accountSearch . '%');
        }
        
        $usersQuery = User::query()->orderBy('name');
        if ($this->account_id) {
            $usersQuery->whereHas('accounts', fn($q) => $q->where('accounts.id', $this->account_id));
        }
        if ($this->userSearch) {
            $usersQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->userSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->userSearch . '%');
            });
        }
        
        return [
            'accounts' => $accountsQuery->limit(50)->get(),
            'users' => $usersQuery->limit(50)->get(),
            'channels' => TicketChannel::where('active', true)->orderBy('list_order')->get(),
            'statuses' => TicketStatus::where('active', true)->orderBy('list_order')->get(),
            'types' => TicketType::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $ticket ? __('Ticket bewerken') : __('Nieuw ticket') }}</flux:heading>
        <flux:subheading>{{ $ticket ? __('Wijzig ticket details') : __('Maak een nieuw support ticket aan') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="title"
            :label="__('Titel')"
            type="text"
            required
            autofocus
            :placeholder="__('Korte omschrijving van het probleem...')"
        />

        <flux:field>
            <flux:label>{{ __('Beschrijving') }}</flux:label>
            <flux:textarea
                wire:model="content"
                rows="6"
                required
                :placeholder="__('Gedetailleerde beschrijving van het probleem...')"
            />
        </flux:field>
        
        <div class="grid gap-6 md:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Account') }}</flux:label>
                <flux:select wire:model.live="account_id" variant="combobox" placeholder="{{ __('Selecteer account') }}" :filter="false">
                    <x-slot name="input">
                        <flux:select.input wire:model.live.debounce.300ms="accountSearch" placeholder="{{ __('Zoek account...') }}" />
                    </x-slot>
                    @foreach($accounts as $account)
                        <flux:select.option value="{{ $account->id }}" wire:key="account-{{ $account->id }}">{{ $account->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Gebruiker') }}</flux:label>
                <flux:select 
                    wire:model.live="user_id" 
                    variant="combobox" 
                    placeholder="{{ __('Selecteer gebruiker') }}"
                    :filter="false"
                    :disabled="!$account_id"
                >
                    <x-slot name="input">
                        <flux:select.input wire:model.live.debounce.300ms="userSearch" placeholder="{{ __('Zoek gebruiker...') }}" :disabled="!$account_id" />
                    </x-slot>
                    @if($account_id)
                        @foreach($users as $user)
                            <flux:select.option value="{{ $user->id }}" wire:key="user-{{ $user->id }}">
                                {{ $user->name }} <span class="text-xs text-zinc-500">({{ $user->email }})</span>
                            </flux:select.option>
                        @endforeach
                    @else
                        <flux:select.option disabled>{{ __('Selecteer eerst een account') }}</flux:select.option>
                    @endif
                </flux:select>
            </flux:field>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <flux:field>
                <flux:label>{{ __('Kanaal') }}</flux:label>
                <flux:select wire:model="channel_id">
                    <option value="">{{ __('Selecteer kanaal') }}</option>
                    @foreach($channels as $channel)
                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Status') }}</flux:label>
                <flux:select wire:model="status_id">
                    <option value="">{{ __('Selecteer status') }}</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Type') }}</flux:label>
                <flux:select wire:model="type_id">
                    <option value="">{{ __('Selecteer type') }}</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('tickets.index')" wire:navigate>
                {{ __('Annuleren') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $ticket ? __('Ticket bijwerken') : __('Ticket aanmaken') }}
            </flux:button>
        </div>
    </form>

    @if($ticket)
        <div class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">{{ __('Berichten') }}</flux:heading>

            <form wire:submit="addMessage" class="space-y-3">
                <flux:field>
                    <flux:label>{{ __('Reactie plaatsen') }}</flux:label>
                    <flux:textarea
                        wire:model.defer="newMessage"
                        rows="4"
                        required
                        :placeholder="__('Schrijf een reactie...')"
                    />
                </flux:field>
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">
                        {{ __('Plaats reactie') }}
                    </flux:button>
                </div>
            </form>

            <div class="space-y-4">
                @forelse($ticket->messages()->with('user')->latest()->get() as $message)
                    <div wire:key="message-{{ $message->id }}" class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="font-medium">{{ $message->user->name }}</div>
                                <div class="text-sm text-zinc-500">
                                    {{ $message->created_at->format('d-m-Y H:i') }}
                                    @if($message->created_at != $message->updated_at)
                                        <span class="text-zinc-400">({{ __('bewerkt') }})</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($message->is_internal)
                                    <flux:badge variant="warning" size="sm">{{ __('Intern') }}</flux:badge>
                                @endif
                                @if($message->user_id === auth()->id())
                                    @if($editingMessageId === $message->id)
                                        <flux:button wire:click="updateMessage" size="sm" variant="primary">
                                            {{ __('Opslaan') }}
                                        </flux:button>
                                        <flux:button wire:click="cancelEdit" size="sm" variant="ghost">
                                            {{ __('Annuleren') }}
                                        </flux:button>
                                    @else
                                        <flux:button wire:click="editMessage({{ $message->id }})" size="sm" variant="ghost">
                                            {{ __('Bewerken') }}
                                        </flux:button>
                                        <flux:modal.trigger :name="'delete-message-'.$message->id">
                                            <flux:button size="sm" variant="danger">
                                                {{ __('Verwijderen') }}
                                            </flux:button>
                                        </flux:modal.trigger>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @if($editingMessageId === $message->id)
                            <div class="mt-3">
                                <flux:textarea
                                    wire:model.defer="editingMessageContent"
                                    rows="4"
                                    required
                                    autofocus
                                />
                            </div>
                        @else
                            <div class="mt-2 text-sm whitespace-pre-wrap">{{ $message->message }}</div>
                        @endif
                    </div>

                    <flux:modal :name="'delete-message-'.$message->id" class="min-w-[22rem]">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">{{ __('Reactie verwijderen?') }}</flux:heading>
                                <flux:text class="mt-2">
                                    {{ __('Deze actie kan niet ongedaan worden gemaakt.') }}
                                </flux:text>
                            </div>
                            <div class="flex gap-2">
                                <flux:spacer />
                                <flux:modal.close>
                                    <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                                </flux:modal.close>
                                <flux:button wire:click="deleteMessage({{ $message->id }})" variant="danger">{{ __('Verwijderen') }}</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @empty
                    <div class="text-center text-sm text-zinc-500">{{ __('Nog geen berichten') }}</div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">{{ __('Bijlagen') }}</flux:heading>

            <form wire:submit="uploadAttachments" class="space-y-3">
                <flux:field>
                    <flux:label>{{ __('Bestanden uploaden') }}</flux:label>
                    <flux:input
                        type="file"
                        wire:model="attachments"
                        multiple
                        accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,application/pdf"
                    />
                    <flux:description>{{ __('Alleen afbeeldingen (JPG, PNG, GIF, WebP) en PDF. Max 10MB per bestand.') }}</flux:description>
                    @error('attachments.*') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                @if($attachments)
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="attachments,uploadAttachments">
                            <span wire:loading.remove wire:target="uploadAttachments">{{ __('Upload bestanden') }}</span>
                            <span wire:loading wire:target="uploadAttachments">{{ __('Uploaden...') }}</span>
                        </flux:button>
                    </div>
                @endif
            </form>
            
            <div class="space-y-2">
                @forelse($ticket->attachments()->with('user')->latest()->get() as $attachment)
                    <div wire:key="attachment-{{ $attachment->id }}" class="flex items-center justify-between rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                        <div class="flex items-center gap-3">
                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($attachment->path) }}" alt="{{ $attachment->original_filename }}" class="h-12 w-12 rounded object-cover" />
                            @else
                                <flux:icon.document class="h-12 w-12 text-red-500" />
                            @endif
                            <div>
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($attachment->path) }}" target="_blank" class="text-sm font-medium hover:underline">{{ $attachment->original_filename }}</a>
                                <div class="text-xs text-zinc-500">{{ number_format($attachment->size / 1024, 2) }} KB • {{ $attachment->user->name }} • {{ $attachment->created_at->format('d-m-Y H:i') }}</div>
                            </div>
                        </div>
                        @if($attachment->user_id === auth()->id())
                            <flux:modal.trigger :name="'delete-attachment-'.$attachment->id">
                                <flux:button size="sm" variant="danger">
                                    {{ __('Verwijderen') }}
                                </flux:button>
                            </flux:modal.trigger>
                        @endif
                    </div>

                    <flux:modal :name="'delete-attachment-'.$attachment->id" class="min-w-[22rem]">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">{{ __('Bijlage verwijderen?') }}</flux:heading>
                                <flux:text class="mt-2">
                                    {{ __('Weet je zeker dat je "') }}{{ $attachment->original_filename }}{{ __('" wilt verwijderen?') }}
                                </flux:text>
                            </div>
                            <div class="flex gap-2">
                                <flux:spacer />
                                <flux:modal.close>
                                    <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                                </flux:modal.close>
                                <flux:button wire:click="deleteAttachment({{ $attachment->id }})" variant="danger">{{ __('Verwijderen') }}</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @empty
                    <div class="text-center text-sm text-zinc-500">{{ __('Nog geen bijlagen') }}</div>
                @endforelse
            </div>
        </div>
    @endif
</div>
