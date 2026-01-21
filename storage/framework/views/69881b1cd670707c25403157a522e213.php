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
     <?php $__env->slot('title', null, []); ?> <?php echo e(__('Over Ons')); ?> <?php $__env->endSlot(); ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-bold text-zinc-900 dark:text-zinc-100 sm:text-5xl mb-6">
                    <?php echo e(__('Over Ons')); ?>

                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    <?php echo e(__('Wij zijn een team van gedreven professionals die passie hebben voor innovatie en excellentie.')); ?>

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
                            <?php if (isset($component)) { $__componentOriginal031dcbf311525f7d428c4ce4d6c7719b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal031dcbf311525f7d428c4ce4d6c7719b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.rocket-launch','data' => ['class' => 'size-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.rocket-launch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal031dcbf311525f7d428c4ce4d6c7719b)): ?>
<?php $attributes = $__attributesOriginal031dcbf311525f7d428c4ce4d6c7719b; ?>
<?php unset($__attributesOriginal031dcbf311525f7d428c4ce4d6c7719b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal031dcbf311525f7d428c4ce4d6c7719b)): ?>
<?php $component = $__componentOriginal031dcbf311525f7d428c4ce4d6c7719b; ?>
<?php unset($__componentOriginal031dcbf311525f7d428c4ce4d6c7719b); ?>
<?php endif; ?>
                        </div>
                        <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                            <?php echo e(__('Onze Missie')); ?>

                        </h2>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            <?php echo e(__('We streven ernaar om innovatieve oplossingen te bieden die bedrijven helpen groeien en succesvoller te worden in een digitale wereld.')); ?>

                        </p>
                    </div>

                    <!-- Our Vision -->
                    <div class="space-y-4">
                        <div class="inline-flex items-center justify-center size-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                            <?php if (isset($component)) { $__componentOriginal2e57535a42d25d5415c31aa83132341b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e57535a42d25d5415c31aa83132341b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.eye','data' => ['class' => 'size-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.eye'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e57535a42d25d5415c31aa83132341b)): ?>
<?php $attributes = $__attributesOriginal2e57535a42d25d5415c31aa83132341b; ?>
<?php unset($__attributesOriginal2e57535a42d25d5415c31aa83132341b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e57535a42d25d5415c31aa83132341b)): ?>
<?php $component = $__componentOriginal2e57535a42d25d5415c31aa83132341b; ?>
<?php unset($__componentOriginal2e57535a42d25d5415c31aa83132341b); ?>
<?php endif; ?>
                        </div>
                        <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                            <?php echo e(__('Onze Visie')); ?>

                        </h2>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            <?php echo e(__('We geloven in een toekomst waarin technologie toegankelijk is voor iedereen en bedrijven van elke omvang kunnen profiteren van digitale innovatie.')); ?>

                        </p>
                    </div>
                </div>

                <!-- Our Values -->
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 text-center mb-12">
                        <?php echo e(__('Onze Waarden')); ?>

                    </h2>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div class="text-center space-y-3">
                            <div class="inline-flex items-center justify-center size-12 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                <?php if (isset($component)) { $__componentOriginaldb480e8d5d7476402b0c7e6f30ee2bdb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb480e8d5d7476402b0c7e6f30ee2bdb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.check-badge','data' => ['class' => 'size-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.check-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldb480e8d5d7476402b0c7e6f30ee2bdb)): ?>
<?php $attributes = $__attributesOriginaldb480e8d5d7476402b0c7e6f30ee2bdb; ?>
<?php unset($__attributesOriginaldb480e8d5d7476402b0c7e6f30ee2bdb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldb480e8d5d7476402b0c7e6f30ee2bdb)): ?>
<?php $component = $__componentOriginaldb480e8d5d7476402b0c7e6f30ee2bdb; ?>
<?php unset($__componentOriginaldb480e8d5d7476402b0c7e6f30ee2bdb); ?>
<?php endif; ?>
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                <?php echo e(__('Kwaliteit')); ?>

                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                <?php echo e(__('We leveren alleen het beste werk en streven naar perfectie in alles wat we doen.')); ?>

                            </p>
                        </div>

                        <div class="text-center space-y-3">
                            <div class="inline-flex items-center justify-center size-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                <?php if (isset($component)) { $__componentOriginal7dbc05838c17e1e397a9753ab5f157f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7dbc05838c17e1e397a9753ab5f157f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.light-bulb','data' => ['class' => 'size-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.light-bulb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7dbc05838c17e1e397a9753ab5f157f6)): ?>
<?php $attributes = $__attributesOriginal7dbc05838c17e1e397a9753ab5f157f6; ?>
<?php unset($__attributesOriginal7dbc05838c17e1e397a9753ab5f157f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7dbc05838c17e1e397a9753ab5f157f6)): ?>
<?php $component = $__componentOriginal7dbc05838c17e1e397a9753ab5f157f6; ?>
<?php unset($__componentOriginal7dbc05838c17e1e397a9753ab5f157f6); ?>
<?php endif; ?>
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                <?php echo e(__('Innovatie')); ?>

                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                <?php echo e(__('We blijven altijd vooruit kijken en zoeken naar nieuwe en betere manieren om problemen op te lossen.')); ?>

                            </p>
                        </div>

                        <div class="text-center space-y-3">
                            <div class="inline-flex items-center justify-center size-12 rounded-lg bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400">
                                <?php if (isset($component)) { $__componentOriginalfcc604edd6e541ab058ff166c8353443 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfcc604edd6e541ab058ff166c8353443 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.heart','data' => ['class' => 'size-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.heart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfcc604edd6e541ab058ff166c8353443)): ?>
<?php $attributes = $__attributesOriginalfcc604edd6e541ab058ff166c8353443; ?>
<?php unset($__attributesOriginalfcc604edd6e541ab058ff166c8353443); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfcc604edd6e541ab058ff166c8353443)): ?>
<?php $component = $__componentOriginalfcc604edd6e541ab058ff166c8353443; ?>
<?php unset($__componentOriginalfcc604edd6e541ab058ff166c8353443); ?>
<?php endif; ?>
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                <?php echo e(__('Passie')); ?>

                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                <?php echo e(__('We houden van wat we doen en dat zie je terug in ons werk en onze toewijding.')); ?>

                            </p>
                        </div>
                    </div>
                </div>
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
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/frontend/about.blade.php ENDPATH**/ ?>