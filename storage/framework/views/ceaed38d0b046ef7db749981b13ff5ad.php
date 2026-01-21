<?php if (isset($component)) { $__componentOriginalcce5292e18037f81915ce4af9fa20b8d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcce5292e18037f81915ce4af9fa20b8d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.frontend','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.frontend'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-zinc-800 dark:to-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">
                    Welkom bij <?php echo e(config('app.name')); ?>

                </h1>
                <p class="text-xl text-zinc-600 dark:text-zinc-400 mb-8 leading-relaxed">
                    Uw partner in digitale oplossingen. Wij helpen u bij het realiseren van uw online ambities met professionele en innovatieve diensten.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('frontend.about')); ?>" wire:navigate class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        Ontdek meer
                    </a>
                    <a href="<?php echo e(route('frontend.contact')); ?>" wire:navigate class="px-8 py-3 bg-white border-2 border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                        Neem contact op
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">
                    Onze Diensten
                </h2>
                <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                    Ontdek hoe wij u kunnen helpen met onze professionele diensten
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 border border-zinc-200 dark:border-zinc-700 hover:shadow-lg transition-shadow">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mb-4">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2">
                        Snel & Betrouwbaar
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400">
                        Hoogwaardige oplossingen die snel worden geleverd zonder concessies te doen aan kwaliteit.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 border border-zinc-200 dark:border-zinc-700 hover:shadow-lg transition-shadow">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mb-4">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2">
                        Op Maat Gemaakt
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400">
                        Elk project wordt specifiek voor uw behoeften ontwikkeld met aandacht voor detail.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 border border-zinc-200 dark:border-zinc-700 hover:shadow-lg transition-shadow">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mb-4">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2">
                        Veilig & Betrouwbaar
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400">
                        Uw gegevens en privacy zijn bij ons in veilige handen met de nieuwste beveiligingsstandaarden.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Callback Request Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('callback-request', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3494541207-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-zinc-100 dark:bg-zinc-900 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">
                    Klaar om te starten?
                </h2>
                <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-8">
                    Neem vandaag nog contact met ons op en ontdek hoe wij u kunnen helpen.
                </p>
                <a href="<?php echo e(route('frontend.contact')); ?>" wire:navigate class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Contact opnemen
                </a>
            </div>
        </div>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcce5292e18037f81915ce4af9fa20b8d)): ?>
<?php $attributes = $__attributesOriginalcce5292e18037f81915ce4af9fa20b8d; ?>
<?php unset($__attributesOriginalcce5292e18037f81915ce4af9fa20b8d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcce5292e18037f81915ce4af9fa20b8d)): ?>
<?php $component = $__componentOriginalcce5292e18037f81915ce4af9fa20b8d; ?>
<?php unset($__componentOriginalcce5292e18037f81915ce4af9fa20b8d); ?>
<?php endif; ?>
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/frontend/home.blade.php ENDPATH**/ ?>