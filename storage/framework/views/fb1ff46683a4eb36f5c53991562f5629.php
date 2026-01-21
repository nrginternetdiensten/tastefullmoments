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
                    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-6">
                        Welkom, <?php echo e(Auth::user()->name); ?>

                    </h2>

                    <div class="space-y-6">
                        <!-- Quick Stats -->
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Account Status</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->email_verified_at): ?>
                                    <p class="text-lg font-semibold text-green-600">Geverifieerd</p>
                                <?php else: ?>
                                    <p class="text-lg font-semibold text-orange-600">Niet Geverifieerd</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Lid sinds</p>
                                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100"><?php echo e(Auth::user()->created_at->format('d-m-Y')); ?></p>
                            </div>
                            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">E-mailadres</p>
                                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 truncate"><?php echo e(Auth::user()->email); ?></p>
                            </div>
                        </div>

                        <!-- Account Balances -->
                        <?php if(Auth::user()->accounts->count() > 0): ?>
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                                    <?php echo e(Auth::user()->accounts->count() === 1 ? 'Account Saldo' : 'Account Saldo\'s'); ?>

                                </h3>
                                <div class="space-y-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = Auth::user()->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-zinc-900 dark:to-zinc-800 rounded-lg border border-blue-200 dark:border-zinc-700">
                                            <div class="flex items-center gap-3">
                                                <div class="flex size-10 items-center justify-center rounded-lg bg-blue-600 text-white">
                                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                    </svg>
                                                </div>
                                                <?php if(Auth::user()->accounts->count() > 1): ?>
                                                    <div>
                                                        <p class="font-medium text-zinc-900 dark:text-zinc-100"><?php echo e($account->name); ?></p>
                                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Account #<?php echo e($account->id); ?></p>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">Huidig Saldo</p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold <?php echo e($account->balance_cents >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>">
                                                    <?php echo e($account->balance_cents >= 0 ? '' : '-'); ?>€<?php echo e(number_format(abs($account->balance_cents) / 100, 2, ',', '.')); ?>

                                                </p>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($account->credit_limit_cents): ?>
                                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                        Credit limiet: €<?php echo e(number_format($account->credit_limit_cents / 100, 2, ',', '.')); ?>

                                                    </p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Quick Actions -->
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Snelle Acties</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <a href="<?php echo e(route('account.profile')); ?>" wire:navigate class="flex items-center gap-4 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                    <div class="flex size-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">Profiel Bewerken</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Wijzig uw persoonlijke gegevens</p>
                                    </div>
                                </a>
                                <a href="<?php echo e(route('account.password')); ?>" wire:navigate class="flex items-center gap-4 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                    <div class="flex size-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">Wachtwoord Wijzigen</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Beveilig uw account</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
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
<?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/frontend/account/dashboard.blade.php ENDPATH**/ ?>