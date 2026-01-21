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
     <?php $__env->slot('title', null, []); ?> <?php echo e(__('Contact')); ?> <?php $__env->endSlot(); ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-bold text-zinc-900 dark:text-zinc-100 sm:text-5xl mb-6">
                    <?php echo e(__('Neem Contact Op')); ?>

                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    <?php echo e(__('Heeft u vragen of wilt u meer informatie? We staan voor u klaar!')); ?>

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
                            <?php echo e(__('Stuur ons een bericht')); ?>

                        </h2>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('frontend.contact-form', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3896476028-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">
                                <?php echo e(__('Contactgegevens')); ?>

                            </h2>

                            <div class="space-y-6">
                                <!-- Email -->
                                <div class="flex items-start gap-4">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                        <?php if (isset($component)) { $__componentOriginalb2620669e6f3f9a8ec8b91c4a73fca6f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2620669e6f3f9a8ec8b91c4a73fca6f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.envelope','data' => ['class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.envelope'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2620669e6f3f9a8ec8b91c4a73fca6f)): ?>
<?php $attributes = $__attributesOriginalb2620669e6f3f9a8ec8b91c4a73fca6f; ?>
<?php unset($__attributesOriginalb2620669e6f3f9a8ec8b91c4a73fca6f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2620669e6f3f9a8ec8b91c4a73fca6f)): ?>
<?php $component = $__componentOriginalb2620669e6f3f9a8ec8b91c4a73fca6f; ?>
<?php unset($__componentOriginalb2620669e6f3f9a8ec8b91c4a73fca6f); ?>
<?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                            <?php echo e(__('E-mail')); ?>

                                        </h3>
                                        <a href="mailto:<?php echo setting(7); ?>" class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            <?php echo setting(7); ?>

                                        </a>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start gap-4">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                        <?php if (isset($component)) { $__componentOriginal3b273e6b331c9518de08da49e1886441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b273e6b331c9518de08da49e1886441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.phone','data' => ['class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.phone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b273e6b331c9518de08da49e1886441)): ?>
<?php $attributes = $__attributesOriginal3b273e6b331c9518de08da49e1886441; ?>
<?php unset($__attributesOriginal3b273e6b331c9518de08da49e1886441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b273e6b331c9518de08da49e1886441)): ?>
<?php $component = $__componentOriginal3b273e6b331c9518de08da49e1886441; ?>
<?php unset($__componentOriginal3b273e6b331c9518de08da49e1886441); ?>
<?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                            <?php echo e(__('Telefoonnummer')); ?>

                                        </h3>
                                        <a href="tel:<?php echo setting(6); ?>" class="text-zinc-600 dark:text-zinc-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                            <?php echo setting(6); ?>

                                        </a>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="flex items-start gap-4">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                        <?php if (isset($component)) { $__componentOriginal0d48bd54d72df81b49ee07c1a3735f04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d48bd54d72df81b49ee07c1a3735f04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.map-pin','data' => ['class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.map-pin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d48bd54d72df81b49ee07c1a3735f04)): ?>
<?php $attributes = $__attributesOriginal0d48bd54d72df81b49ee07c1a3735f04; ?>
<?php unset($__attributesOriginal0d48bd54d72df81b49ee07c1a3735f04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d48bd54d72df81b49ee07c1a3735f04)): ?>
<?php $component = $__componentOriginal0d48bd54d72df81b49ee07c1a3735f04; ?>
<?php unset($__componentOriginal0d48bd54d72df81b49ee07c1a3735f04); ?>
<?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                            <?php echo e(__('Adres')); ?>

                                        </h3>
                                        <p class="text-zinc-600 dark:text-zinc-400">
                                            <?php echo setting(2); ?> <?php echo setting(3); ?><br>
                                            <?php echo setting(4); ?> <?php echo setting(5); ?><br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opening Hours -->
                        <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 p-6">
                            <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-4">
                                <?php echo e(__('Openingstijden')); ?>

                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-zinc-600 dark:text-zinc-400"><?php echo e(__('Maandag - Vrijdag')); ?></span>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">09:00 - 17:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-600 dark:text-zinc-400"><?php echo e(__('Weekend')); ?></span>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100"><?php echo e(__('Gesloten')); ?></span>
                                </div>
                            </div>
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
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/frontend/contact.blade.php ENDPATH**/ ?>