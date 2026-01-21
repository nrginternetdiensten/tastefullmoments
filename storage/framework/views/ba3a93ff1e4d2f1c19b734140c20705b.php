<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo app('flux')->fluxAppearance(); ?>

</head>
<body class="bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 antialiased" x-data="{ mobileMenuOpen: false }">
    <!-- Top Navigation -->
    <nav class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="<?php echo e(route('home')); ?>" wire:navigate class="flex items-center gap-2 group">
                        <div class="flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-lg shadow-md group-hover:shadow-lg transition-shadow">
                            <?php echo e(substr(config('app.name'), 0, 1)); ?>

                        </div>
                        <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100"><?php echo e(config('app.name')); ?></span>
                    </a>
                </div>

                <!-- Desktop Navigation - Always visible -->
                <div class="flex items-center space-x-1 ml-8">
                    <a href="<?php echo e(route('home')); ?>" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('home') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                        Home
                    </a>
                    <a href="<?php echo e(route('frontend.about')); ?>" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('frontend.about') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                        Over Ons
                    </a>
                    <a href="<?php echo e(route('frontend.faq')); ?>" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('frontend.faq') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                        Veelgestelde Vragen
                    </a>
                    <a href="<?php echo e(route('frontend.contact')); ?>" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('frontend.contact') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                        Contact
                    </a>
                </div>

                <!-- Desktop Auth - Always visible -->
                <div class="flex items-center space-x-3 ml-auto">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('account.dashboard')); ?>" wire:navigate class="px-3 py-2 text-sm font-medium text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400">
                            Mijn Account
                        </a>
                        <a href="<?php echo e(route('dashboard')); ?>" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-sm font-medium text-white hover:bg-blue-700">
                            CMS Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" wire:navigate class="px-3 py-2 text-sm font-medium text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400">
                            Inloggen
                        </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                            <a href="<?php echo e(route('register')); ?>" wire:navigate class="px-4 py-2 rounded-md bg-blue-600 text-sm font-medium text-white hover:bg-blue-700">
                                Registreren
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <svg x-show="!mobileMenuOpen" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden pb-3 pt-2 space-y-1">
                <a href="<?php echo e(route('home')); ?>" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('home') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                    Home
                </a>
                <a href="<?php echo e(route('frontend.about')); ?>" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('frontend.about') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                    Over Ons
                </a>
                <a href="<?php echo e(route('frontend.faq')); ?>" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('frontend.faq') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                    Veelgestelde Vragen
                </a>
                <a href="<?php echo e(route('frontend.contact')); ?>" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('frontend.contact') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'); ?>">
                    Contact
                </a>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Dashboard
                    </a>
                <?php else: ?>
                    <div class="pt-2 border-t border-zinc-200 dark:border-zinc-700 space-y-1">
                        <a href="<?php echo e(route('login')); ?>" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Inloggen
                        </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                            <a href="<?php echo e(route('register')); ?>" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Registreren
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen pb-3">
        <?php echo e($slot); ?>

    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 mt-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
                <!-- Company Info -->
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xl shadow-lg">
                            <?php echo e(substr(config('app.name'), 0, 1)); ?>

                        </div>
                        <span class="text-xl font-bold"><?php echo e(config('app.name')); ?></span>
                    </div>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                        <?php echo e(__('Uw partner in digitale oplossingen. Wij helpen u bij het realiseren van uw online ambities.')); ?>

                    </p>
                    <!-- Social Media -->
                    <div class="flex gap-3">
                        <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-zinc-200 hover:bg-blue-600 dark:bg-zinc-800 dark:hover:bg-blue-600 text-zinc-700 hover:text-white dark:text-zinc-300 dark:hover:text-white transition-all">
                            <?php if (isset($component)) { $__componentOriginale02ab0f625e6b2501fa40e35388d0046 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale02ab0f625e6b2501fa40e35388d0046 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.globe-alt','data' => ['class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.globe-alt'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale02ab0f625e6b2501fa40e35388d0046)): ?>
<?php $attributes = $__attributesOriginale02ab0f625e6b2501fa40e35388d0046; ?>
<?php unset($__attributesOriginale02ab0f625e6b2501fa40e35388d0046); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale02ab0f625e6b2501fa40e35388d0046)): ?>
<?php $component = $__componentOriginale02ab0f625e6b2501fa40e35388d0046; ?>
<?php unset($__componentOriginale02ab0f625e6b2501fa40e35388d0046); ?>
<?php endif; ?>
                        </a>
                        <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-zinc-200 hover:bg-blue-600 dark:bg-zinc-800 dark:hover:bg-blue-600 text-zinc-700 hover:text-white dark:text-zinc-300 dark:hover:text-white transition-all">
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
                        </a>
                        <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-zinc-200 hover:bg-blue-600 dark:bg-zinc-800 dark:hover:bg-blue-600 text-zinc-700 hover:text-white dark:text-zinc-300 dark:hover:text-white transition-all">
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
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="mb-6 text-base font-bold text-zinc-900 dark:text-zinc-100">
                        <?php echo e(__('Snelle Links')); ?>

                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="<?php echo e(route('home')); ?>" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'size-4 group-hover:translate-x-1 transition-transform']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4 group-hover:translate-x-1 transition-transform']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
                                <?php echo e(__('Home')); ?>

                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('frontend.about')); ?>" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'size-4 group-hover:translate-x-1 transition-transform']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4 group-hover:translate-x-1 transition-transform']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
                                <?php echo e(__('Over Ons')); ?>

                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('frontend.contact')); ?>" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'size-4 group-hover:translate-x-1 transition-transform']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4 group-hover:translate-x-1 transition-transform']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
                                <?php echo e(__('Contact')); ?>

                            </a>
                        </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <li>
                            <a href="<?php echo e(route('frontend.faq')); ?>" wire:navigate class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'size-4 group-hover:translate-x-1 transition-transform']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4 group-hover:translate-x-1 transition-transform']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
                                <?php echo e(__('Veelgestelde vragen')); ?>

                            </a>
                        </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="mb-6 text-base font-bold text-zinc-900 dark:text-zinc-100">
                        <?php echo e(__('Juridisch')); ?>

                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="<?php echo e(route('frontend.content','privacybeleid')); ?>" class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'size-4 group-hover:translate-x-1 transition-transform']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4 group-hover:translate-x-1 transition-transform']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
                                <?php echo e(__('Privacybeleid')); ?>

                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('frontend.content','algemene-voorwaarden')); ?>" class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'size-4 group-hover:translate-x-1 transition-transform']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4 group-hover:translate-x-1 transition-transform']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
                                <?php echo e(__('Algemene Voorwaarden')); ?>

                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('frontend.content','cookiebeleid')); ?>" class="inline-flex items-center gap-2 text-base text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors group">
                                <?php if (isset($component)) { $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.chevron-right','data' => ['class' => 'size-4 group-hover:translate-x-1 transition-transform']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4 group-hover:translate-x-1 transition-transform']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $attributes = $__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__attributesOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3)): ?>
<?php $component = $__componentOriginal31cb76c8d087d4f00797aeea7232b4c3; ?>
<?php unset($__componentOriginal31cb76c8d087d4f00797aeea7232b4c3); ?>
<?php endif; ?>
                                <?php echo e(__('Cookiebeleid')); ?>

                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="mb-6 text-base font-bold text-zinc-900 dark:text-zinc-100">
                        <?php echo e(__('Contact Informatie')); ?>

                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginalb2620669e6f3f9a8ec8b91c4a73fca6f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2620669e6f3f9a8ec8b91c4a73fca6f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.envelope','data' => ['class' => 'size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.envelope'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0']); ?>
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
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-500"><?php echo e(__('Email')); ?></p>
                                <a href="mailto:<?php echo e(setting(7)); ?>" class="text-base text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400 transition-colors">
                                    <?php echo e(setting(7)); ?>

                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginal3b273e6b331c9518de08da49e1886441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b273e6b331c9518de08da49e1886441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.phone','data' => ['class' => 'size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.phone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0']); ?>
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
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-500"><?php echo e(__('Telefoon')); ?></p>
                                <a href="tel:<?php echo e(setting(6)); ?>" class="text-base text-zinc-700 hover:text-blue-600 dark:text-zinc-300 dark:hover:text-blue-400 transition-colors">
                                    <?php echo e(setting(6)); ?>

                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginal0d48bd54d72df81b49ee07c1a3735f04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d48bd54d72df81b49ee07c1a3735f04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.map-pin','data' => ['class' => 'size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.map-pin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0']); ?>
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
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-500"><?php echo e(__('Adres')); ?></p>
                                <p class="text-base text-zinc-700 dark:text-zinc-300">
                                    <?php echo e(setting(2)); ?> <?php echo e(setting(3)); ?><br>
                                    <?php echo e(setting(4)); ?> <?php echo e(setting(5)); ?>

                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-12 border-t border-zinc-200 dark:border-zinc-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 text-center md:text-left">
                        &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. <?php echo e(__('Alle rechten voorbehouden.')); ?>

                    </p>
                    <div class="flex items-center gap-6 text-sm text-zinc-600 dark:text-zinc-400">
                        <span><?php echo e(__('Gemaakt met')); ?> ❤️ <?php echo e(__('in Nederland')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php app('livewire')->forceAssetInjection(); ?>
<?php echo app('flux')->scripts(); ?>

</body>
</html>
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/components/layouts/frontend.blade.php ENDPATH**/ ?>