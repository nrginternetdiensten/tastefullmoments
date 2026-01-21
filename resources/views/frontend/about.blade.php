<x-layouts.frontend>
    <x-slot name="title">{{ __('Over Ons') }}</x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-bold text-zinc-900 dark:text-zinc-100 sm:text-5xl mb-6">
                    {{ __('Over Ons') }}
                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    {{ __('Wij zijn een team van gedreven professionals die passie hebben voor innovatie en excellentie.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="grid gap-12 md:grid-cols-2">
                    <!-- Our Mission -->
                    <div class="space-y-4">
                        <div class="inline-flex items-center justify-center size-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                            <flux:icon.rocket-launch class="size-6" />
                        </div>
                        <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('Onze Missie') }}
                        </h2>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            {{ __('We streven ernaar om innovatieve oplossingen te bieden die bedrijven helpen groeien en succesvoller te worden in een digitale wereld.') }}
                        </p>
                    </div>

                    <!-- Our Vision -->
                    <div class="space-y-4">
                        <div class="inline-flex items-center justify-center size-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                            <flux:icon.eye class="size-6" />
                        </div>
                        <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('Onze Visie') }}
                        </h2>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            {{ __('We geloven in een toekomst waarin technologie toegankelijk is voor iedereen en bedrijven van elke omvang kunnen profiteren van digitale innovatie.') }}
                        </p>
                    </div>
                </div>

                <!-- Our Values -->
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 text-center mb-12">
                        {{ __('Onze Waarden') }}
                    </h2>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div class="text-center space-y-3">
                            <div class="inline-flex items-center justify-center size-12 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                <flux:icon.check-badge class="size-6" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Kwaliteit') }}
                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __('We leveren alleen het beste werk en streven naar perfectie in alles wat we doen.') }}
                            </p>
                        </div>

                        <div class="text-center space-y-3">
                            <div class="inline-flex items-center justify-center size-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                <flux:icon.light-bulb class="size-6" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Innovatie') }}
                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __('We blijven altijd vooruit kijken en zoeken naar nieuwe en betere manieren om problemen op te lossen.') }}
                            </p>
                        </div>

                        <div class="text-center space-y-3">
                            <div class="inline-flex items-center justify-center size-12 rounded-lg bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400">
                                <flux:icon.heart class="size-6" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Passie') }}
                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __('We houden van wat we doen en dat zie je terug in ons werk en onze toewijding.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.frontend>
