<?php if (isset($component)) { $__componentOriginalcce5292e18037f81915ce4af9fa20b8d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcce5292e18037f81915ce4af9fa20b8d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.frontend','data' => ['title' => $content->seo_title ?? $content->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.frontend'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($content->seo_title ?? $content->name)]); ?>
    <div class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->folder): ?>
                <nav class="mb-8 flex" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 text-sm">
                        <li>
                            <a href="<?php echo e(route('home')); ?>" wire:navigate class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300">
                                Home
                            </a>
                        </li>
                        <li class="text-zinc-400 dark:text-zinc-600">/</li>
                        <li>
                            <span class="text-zinc-700 dark:text-zinc-300"><?php echo e($content->folder->name); ?></span>
                        </li>
                        <li class="text-zinc-400 dark:text-zinc-600">/</li>
                        <li>
                            <span class="text-zinc-900 dark:text-zinc-100 font-medium"><?php echo e($content->name); ?></span>
                        </li>
                    </ol>
                </nav>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Content Header -->
            <div class="mb-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->type): ?>
                    <div class="mb-3">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium <?php echo e($content->type->colorScheme->bg_class ?? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'); ?>">
                            <?php echo e($content->type->name); ?>

                        </span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <h1 class="text-4xl lg:text-5xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">
                    <?php echo e($content->name); ?>

                </h1>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->seo_description): ?>
                    <p class="text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        <?php echo e($content->seo_description); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <article class="prose prose-zinc dark:prose-invert max-w-none p-8 lg:p-12">
                    <?php echo $content->content; ?>

                </article>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="<?php echo e(route('home')); ?>" wire:navigate class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Terug naar home
                </a>
            </div>
        </div>
    </div>
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
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/frontend/content.blade.php ENDPATH**/ ?>