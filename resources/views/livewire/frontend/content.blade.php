@volt
<?php

use function Livewire\Volt\{state, mount, computed};
use App\Models\ContentItem;

state(['seoUrl']);

mount(function () {
    $this->seoUrl = $this->seoUrl ?? request()->route('seoUrl');
});

$content = computed(function () {
    return ContentItem::with(['folder', 'type.colorScheme'])
        ->where('seo_url', $this->seoUrl)
        ->firstOrFail();
});

?>

<x-layouts.frontend :title="$this->content->seo_title ?? $this->content->name">
    <div class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            @if($this->content->folder)
                <nav class="mb-8 flex" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 text-sm">
                        <li>
                            <a href="{{ route('home') }}" wire:navigate class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300">
                                Home
                            </a>
                        </li>
                        <li class="text-zinc-400 dark:text-zinc-600">/</li>
                        <li>
                            <span class="text-zinc-700 dark:text-zinc-300">{{ $this->content->folder->name }}</span>
                        </li>
                        <li class="text-zinc-400 dark:text-zinc-600">/</li>
                        <li>
                            <span class="text-zinc-900 dark:text-zinc-100 font-medium">{{ $this->content->name }}</span>
                        </li>
                    </ol>
                </nav>
            @endif

            <!-- Content Header -->
            <div class="mb-8">
                @if($this->content->type)
                    <div class="mb-3">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $this->content->type->colorScheme->bg_class ?? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                            {{ $this->content->type->name }}
                        </span>
                    </div>
                @endif

                <h1 class="text-4xl lg:text-5xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">
                    {{ $this->content->name }}
                </h1>

                @if($this->content->seo_description)
                    <p class="text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $this->content->seo_description }}
                    </p>
                @endif
            </div>

            <!-- Main Content -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <article class="prose prose-zinc dark:prose-invert max-w-none p-8 lg:p-12">
                    {!! $this->content->content !!}
                </article>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Terug naar home
                </a>
            </div>
        </div>
    </div>
</x-layouts.frontend>
@endvolt
