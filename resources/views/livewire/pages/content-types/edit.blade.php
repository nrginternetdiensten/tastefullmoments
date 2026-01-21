<?php

use App\Models\ContentType;
use App\Models\ColorScheme;
use Livewire\Volt\Component;

new class extends Component {
    public ContentType $contentType;
    public string $name = '';
    public ?int $color_scheme_id = null;
    public bool $active = true;
    public int $list_order = 10;

    public function mount(): void
    {
        $this->name = $this->contentType->name;
        $this->color_scheme_id = $this->contentType->color_scheme_id;
        $this->active = $this->contentType->active;
        $this->list_order = $this->contentType->list_order;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'color_scheme_id' => ['nullable', 'exists:color_schemes,id'],
            'active' => ['boolean'],
            'list_order' => ['required', 'integer', 'min:0'],
        ]);

        $this->contentType->update($validated);

        $this->redirect(route('content-types.index'), navigate: true);
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
        <flux:heading size="xl">{{ __('Edit Content Type') }}</flux:heading>
        <flux:subheading>{{ __('Update content type details') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            :placeholder="__('Content Type Name')"
        />

        <flux:field>
            <flux:label>{{ __('Color Scheme') }}</flux:label>
            <flux:description>{{ __('Optional: Select a predefined color combination') }}</flux:description>

            <div class="mt-2 flex max-h-48 flex-wrap gap-2 overflow-y-auto rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <label class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border-2 transition {{ !$color_scheme_id ? 'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800' : 'border-zinc-300 hover:border-zinc-400 dark:border-zinc-600 dark:hover:border-zinc-500' }} bg-zinc-100 dark:bg-zinc-800" title="No color scheme">
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

        <div class="grid grid-cols-2 gap-4">
            <flux:input
                wire:model="list_order"
                :label="__('Display Order')"
                type="number"
                required
                min="0"
            />

            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Status') }}</label>
                <flux:checkbox wire:model="active" :label="__('Active')" />
            </div>
        </div>

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('content-types.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Update Type') }}
            </flux:button>
        </div>
    </form>
</div>
