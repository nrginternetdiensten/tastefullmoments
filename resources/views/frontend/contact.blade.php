<x-layouts.frontend>
    <x-slot name="title">{{ __('Contact') }}</x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-bold text-zinc-900 dark:text-zinc-100 sm:text-5xl mb-6">
                    {{ __('Neem Contact Op') }}
                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    {{ __('Heeft u vragen of wilt u meer informatie? We staan voor u klaar!') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="grid gap-12 lg:grid-cols-2">
                    <!-- Contact Form -->
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">
                            {{ __('Stuur ons een bericht') }}
                        </h2>
                        <livewire:frontend.contact-form />
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">
                                {{ __('Contactgegevens') }}
                            </h2>

                            <div class="space-y-6">
                                <!-- Email -->
                                <div class="flex items-start gap-4">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                        <flux:icon.envelope class="size-5" />
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                            {{ __('E-mail') }}
                                        </h3>
                                        <a href="mailto:{!! setting(7) !!}" class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {!! setting(7) !!}
                                        </a>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start gap-4">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                        <flux:icon.phone class="size-5" />
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                            {{ __('Telefoonnummer') }}
                                        </h3>
                                        <a href="tel:{!! setting(6) !!}" class="text-zinc-600 dark:text-zinc-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                            {!! setting(6) !!}
                                        </a>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="flex items-start gap-4">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                        <flux:icon.map-pin class="size-5" />
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                            {{ __('Adres') }}
                                        </h3>
                                        <p class="text-zinc-600 dark:text-zinc-400">
                                            {!! setting(2) !!} {!! setting(3) !!}<br>
                                            {!! setting(4) !!} {!! setting(5) !!}<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opening Hours -->
                        <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 p-6">
                            <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                                {{ __('Openingstijden') }}
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-zinc-600 dark:text-zinc-400">{{ __('Maandag - Vrijdag') }}</span>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">09:00 - 17:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-600 dark:text-zinc-400">{{ __('Weekend') }}</span>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Gesloten') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.frontend>
