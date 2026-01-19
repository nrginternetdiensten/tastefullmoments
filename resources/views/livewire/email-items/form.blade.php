<?php

use App\Models\EmailItem;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?EmailItem $emailItem = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $subject = '';

    #[Modelable]
    public string $content = '';

    #[Modelable]
    public ?int $folder_id = null;

    public function mount(): void
    {
        if ($this->emailItem) {
            $this->name = $this->emailItem->name;
            $this->subject = $this->emailItem->subject;
            $this->content = $this->emailItem->content;
            $this->folder_id = $this->emailItem->folder_id;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'folder_id' => ['required', 'exists:email_folders,id'],
        ]);

        if ($this->emailItem) {
            $this->emailItem->update($validated);
            $message = 'Email item updated successfully.';
        } else {
            EmailItem::create($validated);
            $message = 'Email item created successfully.';
        }

        $this->dispatch('email-item-saved');

        $this->redirect(route('email-items.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'folders' => \App\Models\EmailFolder::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $emailItem ? __('Edit Email Item') : __('Create Email Item') }}</flux:heading>
        <flux:subheading>{{ $emailItem ? __('Update email item details') : __('Add a new email item') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            :placeholder="__('Item name')"
        />

        <flux:input
            wire:model="subject"
            :label="__('Subject')"
            type="text"
            required
            :placeholder="__('Email subject')"
        />

        <flux:textarea
            wire:model="content"
            :label="__('Content')"
            rows="6"
            required
            :placeholder="__('Email content')"
        />

        <flux:select
            wire:model="folder_id"
            :label="__('Folder')"
            variant="listbox"
            required
            :placeholder="__('Choose folder...')"
        >
            @foreach($folders as $folder)
                <flux:select.option value="{{ $folder->id }}">{{ $folder->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('email-items.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $emailItem ? __('Update Item') : __('Create Item') }}
            </flux:button>
        </div>
    </form>
</div>
