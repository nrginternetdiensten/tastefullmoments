<?php

use App\Models\{ColorScheme, EmailFolder};
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?EmailFolder $emailFolder = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $description = '';

    #[Modelable]
    public ?string $color_scheme_id = null;

    public function mount(): void
    {
        if ($this->emailFolder) {
            $this->name = $this->emailFolder->name;
            $this->description = $this->emailFolder->description ?? '';
            $this->color_scheme_id = $this->emailFolder->color_scheme_id ? (string) $this->emailFolder->color_scheme_id : null;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color_scheme_id' => ['nullable', 'exists:color_schemes,id'],
        ]);

        if ($this->emailFolder) {
            $this->emailFolder->update($validated);
            $message = 'Email folder updated successfully.';
        } else {
            EmailFolder::create($validated);
            $message = 'Email folder created successfully.';
        }

        $this->dispatch('email-folder-saved');

        $this->redirect(route('email-folders.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'colorSchemes' => ColorScheme::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $emailFolder ? __('Edit Email Folder') : __('Create Email Folder') }}</flux:heading>
        <flux:subheading>{{ $emailFolder ? __('Update email folder details') : __('Add a new email folder') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            :placeholder="__('Inbox, Sent, Archive...')"
        />

        <flux:textarea
            wire:model="description"
            :label="__('Description')"
            rows="3"
            :placeholder="__('Optional description for this folder')"
        />

        <flux:field>
            <flux:label>{{ __('Color Scheme') }}</flux:label>
            <flux:description>{{ __('Optioneel: Selecteer een voorgedefinieerde kleurencombinatie') }}</flux:description>

            <div class="mt-2 flex max-h-48 flex-wrap gap-2 overflow-y-auto rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <label class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border-2 transition {{ !$color_scheme_id ? 'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800' : 'border-zinc-300 hover:border-zinc-400 dark:border-zinc-600 dark:hover:border-zinc-500' }} bg-zinc-100 dark:bg-zinc-800" title="Geen kleurencombinatie">
                    <input type="radio" wire:model.live="color_scheme_id" value="" class="sr-only">
                    <span class="text-xl text-zinc-400">×</span>
                </label>
                
                @foreach($colorSchemes as $scheme)
                    <label class="h-10 w-10 cursor-pointer rounded-full border-2 transition {{ $color_scheme_id == $scheme->id ? 'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800' : 'border-white hover:border-zinc-300 dark:border-zinc-900 dark:hover:border-zinc-600' }} {{ $scheme->bg_class }}" title="{{ $scheme->name }}">
                        <input type="radio" wire:model.live="color_scheme_id" value="{{ $scheme->id }}" class="sr-only">
                    </label>
                @endforeach
            </div>
        </flux:field>

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('email-folders.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $emailFolder ? __('Update Folder') : __('Create Folder') }}
            </flux:button>
        </div>
    </form>
</div>
