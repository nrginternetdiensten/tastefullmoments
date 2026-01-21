<x-layouts.frontend>
    <x-slot name="title">{{ __('Veelgestelde Vragen') }}</x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 py-20 dark:from-zinc-800 dark:to-zinc-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="mb-6 text-4xl font-bold text-zinc-900 sm:text-5xl dark:text-zinc-100">
                    {{ __('Veelgestelde Vragen') }}
                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    {{ __('Hier vindt u antwoorden op de meest gestelde vragen') }}
                </p>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl">
                @php
                    $categories = \App\Models\FaqCategory::where('active', true)
                        ->with(['faqs' => fn($q) => $q->where('active', true)->orderBy('list_order')])
                        ->orderBy('list_order')
                        ->get();
                @endphp

                @forelse($categories as $category)
                    @if($category->faqs->count() > 0)
                        <div class="mb-12">
                            <h2 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $category->name }}
                            </h2>

                            @if($category->description)
                                <p class="mb-6 text-zinc-600 dark:text-zinc-400">
                                    {{ $category->description }}
                                </p>
                            @endif

                            <div class="space-y-4">
                                @foreach($category->faqs as $faq)
                                    <details class="group rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                                        <summary class="flex cursor-pointer items-center justify-between p-6 font-medium text-zinc-900 dark:text-zinc-100">
                                            <span>{{ $faq->question }}</span>
                                            <flux:icon.chevron-down class="size-5 text-zinc-500 transition group-open:rotate-180" />
                                        </summary>
                                        <div class="border-t border-zinc-200 p-6 dark:border-zinc-700">
                                            <div class="prose prose-zinc max-w-none dark:prose-invert">
                                                <p>{{ $faq->answer }}</p>
                                            </div>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-12 text-center dark:border-zinc-700 dark:bg-zinc-800">
                        <flux:icon.question-mark-circle class="mx-auto mb-4 size-12 text-zinc-400" />
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">
                            {{ __('Er zijn momenteel geen veelgestelde vragen beschikbaar.') }}
                        </p>
                    </div>
                @endforelse

                <!-- Contact CTA -->
                <div class="mt-12 rounded-lg border border-blue-200 bg-blue-50 p-8 text-center dark:border-blue-800 dark:bg-blue-900/20">
                    <h3 class="mb-2 text-xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ __('Staat uw vraag er niet bij?') }}
                    </h3>
                    <p class="mb-4 text-zinc-600 dark:text-zinc-400">
                        {{ __('Neem gerust contact met ons op. We helpen u graag verder!') }}
                    </p>
                    <flux:button variant="primary" :href="route('frontend.contact')">
                        {{ __('Neem contact op') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.frontend>
