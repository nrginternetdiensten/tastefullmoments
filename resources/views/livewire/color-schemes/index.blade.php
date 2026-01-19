<?php

use App\Models\ColorScheme;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortField = 'list_order';
    public string $sortDirection = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortByField(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete(ColorScheme $colorScheme): void
    {
        $colorScheme->delete();
    }

    public function with(): array
    {
        return [
            'colorSchemes' => ColorScheme::query()
                ->when($this->search, fn($query) => $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('bg_class', 'like', "%{$this->search}%")
                    ->orWhere('text_class', 'like', "%{$this->search}%"))
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Kleurencombinaties') }}</flux:heading>
            <flux:subheading>{{ __('Beheer herbruikbare kleurenschema\'s') }}</flux:subheading>
        </div>
        <flux:button :href="route('color-schemes.create')" wire:navigate variant="primary" icon="plus">
            {{ __('Nieuwe kleurencombinatie') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoek kleurencombinaties...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th
                        wire:click="sortByField('list_order')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Volgorde') }}
                            @if($sortField === 'list_order')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th
                        wire:click="sortByField('name')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Naam') }}
                            @if($sortField === 'name')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Voorbeeld') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Achtergrond') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Tekst') }}
                    </th>
                    <th
                        wire:click="sortByField('active')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300"
                    >
                        <div class="flex items-center gap-1">
                            {{ __('Actief') }}
                            @if($sortField === 'active')
                                <flux:icon.chevron-up :variant="$sortDirection === 'asc' ? 'solid' : 'outline'" class="h-4 w-4" />
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Acties') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse ($colorSchemes as $colorScheme)
                    <tr wire:key="color-scheme-{{ $colorScheme->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $colorScheme->list_order }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $colorScheme->name }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded px-3 py-1 text-sm font-medium {{ $colorScheme->bg_class }} {{ $colorScheme->text_class }}">
                                Voorbeeld
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <code class="rounded bg-zinc-100 px-2 py-1 text-xs dark:bg-zinc-800">{{ $colorScheme->bg_class }}</code>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <code class="rounded bg-zinc-100 px-2 py-1 text-xs dark:bg-zinc-800">{{ $colorScheme->text_class }}</code>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <flux:badge :variant="$colorScheme->active ? 'success' : 'danger'">
                                {{ $colorScheme->active ? __('Actief') : __('Inactief') }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button :href="route('color-schemes.edit', $colorScheme)" wire:navigate size="sm" variant="ghost" icon="pencil">
                                    {{ __('Bewerken') }}
                                </flux:button>
                                <flux:modal.trigger :name="'delete-color-scheme-'.$colorScheme->id">
                                    <flux:button size="sm" variant="danger" icon="trash">
                                        {{ __('Verwijderen') }}
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Geen kleurencombinaties gevonden.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $colorSchemes->links() }}
    </div>

    @foreach ($colorSchemes as $colorScheme)
        <flux:modal :name="'delete-color-scheme-'.$colorScheme->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Kleurencombinatie verwijderen?') }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __('Je staat op het punt om "' . $colorScheme->name . '" te verwijderen.') }}</p>
                        <p>{{ __('Deze actie kan niet ongedaan worden gemaakt.') }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Annuleren') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $colorScheme->id }})" variant="danger">{{ __('Verwijderen') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
