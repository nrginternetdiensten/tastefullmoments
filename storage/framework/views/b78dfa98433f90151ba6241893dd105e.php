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
     <?php $__env->slot('title', null, []); ?> <?php echo e(__('Veelgestelde Vragen')); ?> <?php $__env->endSlot(); ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 py-20 dark:from-zinc-800 dark:to-zinc-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="mb-6 text-4xl font-bold text-zinc-900 sm:text-5xl dark:text-zinc-100">
                    <?php echo e(__('Veelgestelde Vragen')); ?>

                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    <?php echo e(__('Hier vindt u antwoorden op de meest gestelde vragen')); ?>

                </p>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <?php
                    $categories = \App\Models\FaqCategory::where('active', true)
                        ->with(['faqs' => fn($q) => $q->where('active', true)->orderBy('list_order')])
                        ->orderBy('list_order')
                        ->get();
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->faqs->count() > 0): ?>
                        <div class="mb-12">
                            <h2 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                <?php echo e($category->name); ?>

                            </h2>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->description): ?>
                                <p class="mb-6 text-zinc-600 dark:text-zinc-400">
                                    <?php echo e($category->description); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="space-y-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $category->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <details class="group rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                                        <summary class="flex cursor-pointer items-center justify-between p-6 font-medium text-zinc-900 dark:text-zinc-100">
                                            <span><?php echo e($faq->question); ?></span>
                                            <?php if (isset($component)) { $__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-down','data' => ['class' => 'size-5 text-zinc-500 transition group-open:rotate-180']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 text-zinc-500 transition group-open:rotate-180']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0)): ?>
<?php $attributes = $__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0; ?>
<?php unset($__attributesOriginal298ff21bbc41cebb188cbb18c6c11bc0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0)): ?>
<?php $component = $__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0; ?>
<?php unset($__componentOriginal298ff21bbc41cebb188cbb18c6c11bc0); ?>
<?php endif; ?>
                                        </summary>
                                        <div class="border-t border-zinc-200 p-6 dark:border-zinc-700">
                                            <div class="prose prose-zinc max-w-none dark:prose-invert">
                                                <p><?php echo e($faq->answer); ?></p>
                                            </div>
                                        </div>
                                    </details>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-12 text-center dark:border-zinc-700 dark:bg-zinc-800">
                        <?php if (isset($component)) { $__componentOriginal7ff90a4ec719b449b03bf1ad0e63e8a9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7ff90a4ec719b449b03bf1ad0e63e8a9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.question-mark-circle','data' => ['class' => 'mx-auto mb-4 size-12 text-zinc-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.question-mark-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mx-auto mb-4 size-12 text-zinc-400']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7ff90a4ec719b449b03bf1ad0e63e8a9)): ?>
<?php $attributes = $__attributesOriginal7ff90a4ec719b449b03bf1ad0e63e8a9; ?>
<?php unset($__attributesOriginal7ff90a4ec719b449b03bf1ad0e63e8a9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7ff90a4ec719b449b03bf1ad0e63e8a9)): ?>
<?php $component = $__componentOriginal7ff90a4ec719b449b03bf1ad0e63e8a9; ?>
<?php unset($__componentOriginal7ff90a4ec719b449b03bf1ad0e63e8a9); ?>
<?php endif; ?>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">
                            <?php echo e(__('Er zijn momenteel geen veelgestelde vragen beschikbaar.')); ?>

                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Contact CTA -->
                <div class="mt-12 rounded-lg border border-blue-200 bg-blue-50 p-8 text-center dark:border-blue-800 dark:bg-blue-900/20">
                    <h3 class="mb-2 text-xl font-bold text-zinc-900 dark:text-zinc-100">
                        <?php echo e(__('Staat uw vraag er niet bij?')); ?>

                    </h3>
                    <p class="mb-4 text-zinc-600 dark:text-zinc-400">
                        <?php echo e(__('Neem gerust contact met ons op. We helpen u graag verder!')); ?>

                    </p>
                    <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'primary','href' => route('frontend.contact')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('frontend.contact'))]); ?>
                        <?php echo e(__('Neem contact op')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
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
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/frontend/faq.blade.php ENDPATH**/ ?>