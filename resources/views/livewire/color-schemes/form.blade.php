<?php

use App\Models\ColorScheme;
use Livewire\Volt\Component;

new class extends Component {
    public ?ColorScheme $colorScheme = null;
    public string $name = '';
    public string $bg_class = '';
    public string $text_class = '';
    public bool $active = true;
    public int $list_order = 10;

    public function mount(?ColorScheme $colorScheme = null): void
    {
        if ($colorScheme && $colorScheme->exists) {
            $this->colorScheme = $colorScheme;
            $this->name = $colorScheme->name;
            $this->bg_class = $colorScheme->bg_class;
            $this->text_class = $colorScheme->text_class;
            $this->active = $colorScheme->active;
            $this->list_order = $colorScheme->list_order;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'bg_class' => 'required|string|max:255',
            'text_class' => 'required|string|max:255',
            'active' => 'boolean',
            'list_order' => 'integer|min:0',
        ]);

        if ($this->colorScheme && $this->colorScheme->exists) {
            $this->colorScheme->update($validated);
        } else {
            ColorScheme::create($validated);
        }

        $this->redirect(route('color-schemes.index'), navigate: true);
    }
}; ?>

<div>
    <flux:heading size="xl" class="mb-6">
        {{ $colorScheme?->exists ? 'Kleurencombinatie bewerken' : 'Nieuwe kleurencombinatie' }}
    </flux:heading>

    <form wire:submit="save" class="space-y-6 max-w-2xl">
        <flux:field>
            <flux:label>Naam</flux:label>
            <flux:input wire:model="name" placeholder="Bijv. Primary, Success, Danger" />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>Achtergrond Class</flux:label>
            <flux:input wire:model.live="bg_class" placeholder="Bijv. bg-blue-500" />
            <flux:error name="bg_class" />
            <flux:description>Tailwind CSS class voor achtergrondkleur</flux:description>
        </flux:field>

        <flux:field>
            <flux:label>Tekst Class</flux:label>
            <flux:input wire:model.live="text_class" placeholder="Bijv. text-white" />
            <flux:error name="text_class" />
            <flux:description>Tailwind CSS class voor tekstkleur</flux:description>
        </flux:field>

        @if ($bg_class && $text_class)
            <flux:field>
                <flux:label>Voorbeeld</flux:label>
                <div class="inline-block px-4 py-2 rounded {{ $bg_class }} {{ $text_class }}">
                    Dit is een voorbeeld van de kleurencombinatie
                </div>
            </flux:field>
        @endif

        <flux:field>
            <flux:checkbox wire:model="active" label="Actief" />
        </flux:field>

        <flux:field>
            <flux:label>Volgorde</flux:label>
            <flux:input type="number" wire:model="list_order" min="0" />
            <flux:error name="list_order" />
        </flux:field>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">Opslaan</flux:button>
            <flux:button :href="route('color-schemes.index')" variant="ghost">Annuleren</flux:button>
        </div>
    </form>
</div>
