<?php

use App\Models\{Faq, FaqCategory};
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?string $categoryFilter = null;
    public string $sortBy = 'list_order';
    public string $sortDirection = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
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

    public function delete(Faq $faq): void
    {
        $this->authorize('faqs.destroy');

        $faq->delete();

        $this->dispatch('faq-deleted');
    }

    public function with(): array
    {
        return [
            'faqs' => Faq::query()
                ->with('category')
                ->when($this->search, fn($query) =>
                    $query->where('question', 'like', "%{$this->search}%")
                        ->orWhere('answer', 'like', "%{$this->search}%")
                )
                ->when($this->categoryFilter, fn($query) =>
                    $query->where('faq_category_id', $this->categoryFilter)
                )
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(15),
            'categories' => FaqCategory::where('active', true)->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Veelgestelde Vragen') }}</flux:heading>
        @can('faqs.create')
            <flux:button icon="plus" :href="route('faqs.create')" wire:navigate>
                {{ __('Nieuwe FAQ') }}
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

        <flux:select wire:model.live="categoryFilter" placeholder="{{ __('Alle categorieën') }}" class="w-64">
            <flux:select.option value="">{{ __('Alle categorieën') }}</flux:select.option>
            @foreach($categories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
            @endforeach
        </flux:select>
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
                    <th wire:click="sortByField('question')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Vraag') }}
                        @if($sortBy === 'question')
                            <flux:icon.{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }} class="inline size-4" />
                        @endif
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
                @forelse($faqs as $faq)
                    <tr wire:key="faq-{{ $faq->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $faq->list_order }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $faq->question }}</div>
                            <div class="mt-1">
                                <flux:badge size="sm" variant="outline">{{ $faq->category->name }}</flux:badge>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($faq->active)
                                <flux:badge color="green">{{ __('Actief') }}</flux:badge>
                            @else
                                <flux:badge color="zinc">{{ __('Inactief') }}</flux:badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('faqs.edit')
                                    <flux:button size="sm" :href="route('faqs.edit', $faq)" wire:navigate variant="ghost">
                                        {{ __("Bewerken") }}
                                    </flux:button>
                                @endcan
                                @can('faqs.destroy')
                                    <flux:modal.trigger :name="'delete-faq-'.$faq->id">
                                        <flux:button size="sm" variant="danger">
                                            {{ __("Verwijderen") }}
                                        </flux:button>
                                    </flux:modal.trigger>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Geen veelgestelde vragen gevonden.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $faqs->links() }}

    @foreach ($faqs as $faq)
        <flux:modal :name="'delete-faq-'.$faq->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __("FAQ verwijderen?") }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __("Je staat op het punt om deze FAQ te verwijderen:") }}</p>
                        <p class="font-medium">{{ $faq->question }}</p>
                        <p>{{ __("Deze actie kan niet ongedaan worden gemaakt.") }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __("Annuleren") }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete({{ $faq->id }})" variant="danger">{{ __("Verwijderen") }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
