<?php

use App\Models\{Faq, FaqCategory};
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?Faq $faq = null;

    #[Modelable]
    public ?string $faq_category_id = null;

    #[Modelable]
    public string $question = '';

    #[Modelable]
    public string $answer = '';

    #[Modelable]
    public string $list_order = '10';

    #[Modelable]
    public bool $active = true;

    public function mount(): void
    {
        if ($this->faq) {
            $this->faq_category_id = (string) $this->faq->faq_category_id;
            $this->question = $this->faq->question;
            $this->answer = $this->faq->answer;
            $this->list_order = (string) $this->faq->list_order;
            $this->active = $this->faq->active;
        }
    }

    public function save(): void
    {
        $this->authorize($this->faq ? 'faqs.edit' : 'faqs.create');

        $validated = $this->validate([
            'faq_category_id' => ['required', 'exists:faq_categories,id'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'list_order' => ['required', 'integer', 'min:0'],
            'active' => ['boolean'],
        ]);

        if ($this->faq) {
            $this->faq->update($validated);
        } else {
            Faq::create($validated);
        }

        $this->dispatch('faq-saved');
        $this->redirect(route('faqs.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'categories' => FaqCategory::where('active', true)->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $faq ? __('FAQ bewerken') : __('FAQ aanmaken') }}</flux:heading>
        <flux:subheading>{{ $faq ? __('Wijzig vraag en antwoord') : __('Voeg een nieuwe veelgestelde vraag toe') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:field>
            <flux:label>{{ __('Categorie') }} *</flux:label>
            <flux:select wire:model="faq_category_id" placeholder="{{ __('Selecteer een categorie') }}" required>
                @foreach($categories as $category)
                    <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="faq_category_id" />
        </flux:field>

        <flux:input
            wire:model="question"
            :label="__('Vraag')"
            type="text"
            required
            :placeholder="__('Hoe kan ik...?')"
        />

        <flux:field>
            <flux:label>{{ __('Antwoord') }} *</flux:label>
            <flux:textarea
                wire:model="answer"
                rows="8"
                required
                :placeholder="__('Het antwoord op de vraag...')"
            />
            <flux:error name="answer" />
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
                <flux:description>{{ __('Actieve FAQs worden getoond in de frontend') }}</flux:description>
            </flux:field>
        </div>

        <div class="flex items-center justify-between gap-4">
            <flux:button type="button" variant="ghost" :href="route('faqs.index')" wire:navigate>
                {{ __('Annuleren') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $faq ? __('Bijwerken') : __('Aanmaken') }}
            </flux:button>
        </div>
    </form>
</div>
