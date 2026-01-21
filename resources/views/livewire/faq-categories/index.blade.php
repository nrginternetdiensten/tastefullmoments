<?php

use App\Models\FaqCategory;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'list_order';
    public string $sortDirection = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortByField(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete(FaqCategory $faqCategory): void
    {
        $this->authorize('faq-categories.destroy');

        $faqCategory->delete();

        $this->dispatch('faq-category-deleted');
    }

    public function with(): array
    {
        return [
            'categories' => FaqCategory::query()
                ->withCount('faqs')
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                )
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(15),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('FAQ Categorieën') }}</flux:heading>
        @can('faq-categories.create')
            <flux:button icon="plus" :href="route('faq-categories.create')" wire:navigate>
                {{ __('Nieuwe categorie') }}
            </flux:button>
        @endcan
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Zoeken...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th wire:click="sortByField('list_order')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Volgorde') }}
                        @if($sortBy === 'list_order')
                            <flux:icon.{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }} class="inline size-4" />
                        @endif
                    </th>
                    <th wire:click="sortByField('name')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Naam') }}
                        @if($sortBy === 'name')
                            <flux:icon.{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }} class="inline size-4" />
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Beschrijving') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Aantal FAQs') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Status') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Acties') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($categories as $category)
                    <tr wire:key="category-{{ $category->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $category->list_order }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $category->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-md text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $category->description }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:badge variant="outline">{{ $category->faqs_count }}</flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($category->active)
                                <flux:badge color="green">{{ __('Actief') }}</flux:badge>
                            @else
                                <flux:badge color="zinc">{{ __('Inactief') }}</flux:badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('faq-categories.edit')
                                    <flux:button size="sm" :href="route('faq-categories.edit', $category)" wire:navigate variant="ghost">
                                        {{ __('Bewerken') }}
                                    </flux:button>
                                @endcan
                                @can('faq-categories.destroy')
                                    <flux:modal.trigger :name="'delete-category-'.$category->id">
                                        <flux:button size="sm" variant="danger">
                                            {{ __('Verwijderen') }}
                                        </flux:button>
                                    </flux:modal.trigger>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Geen categorieën gevonden.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}

    @foreach ($categories as $category)
        <flux:modal :name="'delete-category-'.$category->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __("Categorie verwijderen?") }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __("Je staat op het punt om categorie") }} {{ $category->name }} {{ __("te verwijderen.") }}</p>
                        <p>{{ __("Deze actie kan niet ongedaan worden gemaakt.") }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __("Annuleren") }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $category->id }})" variant="danger">{{ __("Verwijderen") }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
