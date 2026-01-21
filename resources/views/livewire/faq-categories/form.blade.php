<?php

use App\Models\FaqCategory;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?FaqCategory $faqCategory = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $description = '';

    #[Modelable]
    public string $list_order = '10';

    #[Modelable]
    public bool $active = true;

    public function mount(): void
    {
        if ($this->faqCategory) {
            $this->name = $this->faqCategory->name;
            $this->description = $this->faqCategory->description ?? '';
            $this->list_order = (string) $this->faqCategory->list_order;
            $this->active = $this->faqCategory->active;
        }
    }

    public function save(): void
    {
        $this->authorize($this->faqCategory ? 'faq-categories.edit' : 'faq-categories.create');

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'list_order' => ['required', 'integer', 'min:0'],
            'active' => ['boolean'],
        ]);

        if ($this->faqCategory) {
            $this->faqCategory->update($validated);
        } else {
            FaqCategory::create($validated);
        }

        $this->dispatch('faq-category-saved');
        $this->redirect(route('faq-categories.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $faqCategory ? __('Categorie bewerken') : __('Categorie aanmaken') }}</flux:heading>
        <flux:subheading>{{ $faqCategory ? __('Wijzig categorie details') : __('Voeg een nieuwe categorie toe') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Naam')"
            type="text"
            required
            autofocus
            :placeholder="__('Algemeen, Technisch, Betalingen...')"
        />

        <flux:field>
            <flux:label>{{ __('Beschrijving') }}</flux:label>
            <flux:textarea
                wire:model="description"
                rows="3"
                :placeholder="__('Optionele beschrijving van de categorie')"
            />
            <flux:error name="description" />
        </flux:field>

        <div class="grid gap-6 md:grid-cols-2">
            <flux:input
                wire:model="list_order"
                :label="__('Volgorde')"
                type="number"
                min="0"
                required
            />

            <flux:field>
                <flux:label>{{ __('Status') }}</flux:label>
                <flux:switch wire:model="active" />
                <flux:description>{{ __('Actieve categorieën worden getoond in de frontend') }}</flux:description>
            </flux:field>
        </div>

        <div class="flex items-center justify-between gap-4">
            <flux:button type="button" variant="ghost" :href="route('faq-categories.index')" wire:navigate>
                {{ __('Annuleren') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $faqCategory ? __('Bijwerken') : __('Aanmaken') }}
            </flux:button>
        </div>
    </form>
</div>
