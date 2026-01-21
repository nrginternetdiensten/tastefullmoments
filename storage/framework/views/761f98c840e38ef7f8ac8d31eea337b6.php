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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mb-8">
            Mijn Account
        </h1>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <?php if (isset($component)) { $__componentOriginal891d8b2665b7c453a51bca8edecbbc95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal891d8b2665b7c453a51bca8edecbbc95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account-sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('account-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal891d8b2665b7c453a51bca8edecbbc95)): ?>
<?php $attributes = $__attributesOriginal891d8b2665b7c453a51bca8edecbbc95; ?>
<?php unset($__attributesOriginal891d8b2665b7c453a51bca8edecbbc95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal891d8b2665b7c453a51bca8edecbbc95)): ?>
<?php $component = $__componentOriginal891d8b2665b7c453a51bca8edecbbc95; ?>
<?php unset($__componentOriginal891d8b2665b7c453a51bca8edecbbc95); ?>
<?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-zinc-800 rounded-lg p-8 border border-zinc-200 dark:border-zinc-700">
                    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2">
                        E-mailadres Wijzigen
                    </h2>
                    <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                        Wijzig het e-mailadres dat is gekoppeld aan uw account. U ontvangt een verificatie e-mail op het nieuwe adres.
                    </p>

                    <!-- Current Email Status -->
                    <div class="mb-6 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->email_verified_at): ?>
                                    <div class="flex size-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                        <svg class="size-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div class="flex size-10 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
                                        <svg class="size-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100"><?php echo e(Auth::user()->email); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->email_verified_at): ?>
                                    <p class="text-sm text-green-600 dark:text-green-400">Geverifieerd op <?php echo e(Auth::user()->email_verified_at->format('d-m-Y')); ?></p>
                                <?php else: ?>
                                    <p class="text-sm text-orange-600 dark:text-orange-400">Nog niet geverifieerd</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo e(route('account.email.update')); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                Nieuw E-mailadres
                            </label>
                            <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                Na het wijzigen van uw e-mailadres moet u het nieuwe adres verifiëren.
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <a href="<?php echo e(route('account.dashboard')); ?>" wire:navigate class="px-6 py-2 text-sm font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100">
                                Annuleren
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                E-mailadres Wijzigen
                            </button>
                        </div>
                    </form>
                </div>
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
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/frontend/account/email.blade.php ENDPATH**/ ?>