<?php

use Livewire\Volt\Component;

new class extends Component
{
    public function modules(): array
    {
        return [
            'platform' => [
                'title' => __('Platform'),
                'modules' => [
                    ['name' => __('Accounts'), 'route' => 'accounts.index', 'icon' => 'building-office-2', 'permission' => 'accounts.index'],
                    ['name' => __('Account Types'), 'route' => 'account-types.index', 'icon' => 'rectangle-stack', 'permission' => 'account-types.index'],
                    ['name' => __('Account Transactions'), 'route' => 'account-transactions.index', 'icon' => 'banknotes', 'permission' => null],
                    ['name' => __('Email Folders'), 'route' => 'email-folders.index', 'icon' => 'folder', 'permission' => 'email-folders.index'],
                    ['name' => __('Email Items'), 'route' => 'email-items.index', 'icon' => 'envelope', 'permission' => 'email-items.index'],
                    ['name' => __('Color Schemes'), 'route' => 'color-schemes.index', 'icon' => 'swatch', 'permission' => 'color-schemes.index'],
                ],
            ],
            'leads' => [
                'title' => __('Leads'),
                'modules' => [
                    ['name' => __('Leads'), 'route' => 'lead-items.index', 'icon' => 'document-text', 'permission' => 'lead-items.index'],
                    ['name' => __('Lead Statuses'), 'route' => 'lead-statuses.index', 'icon' => 'flag', 'permission' => 'lead-statuses.index'],
                    ['name' => __('Lead Categories'), 'route' => 'lead-categories.index', 'icon' => 'tag', 'permission' => 'lead-categories.index'],
                    ['name' => __('Lead Channels'), 'route' => 'lead-channels.index', 'icon' => 'arrow-trending-up', 'permission' => 'lead-channels.index'],
                ],
            ],
            'support' => [
                'title' => __('Support'),
                'modules' => [
                    ['name' => __('Tickets'), 'route' => 'tickets.index', 'icon' => 'ticket', 'permission' => 'tickets.index'],
                    ['name' => __('Ticket Statuses'), 'route' => 'ticket-statuses.index', 'icon' => 'tag', 'permission' => 'ticket-statuses.index'],
                    ['name' => __('Ticket Channels'), 'route' => 'ticket-channels.index', 'icon' => 'chat-bubble-left-right', 'permission' => 'ticket-channels.index'],
                    ['name' => __('Ticket Types'), 'route' => 'ticket-types.index', 'icon' => 'rectangle-stack', 'permission' => 'ticket-types.index'],
                    ['name' => __('FAQ Categorieën'), 'route' => 'faq-categories.index', 'icon' => 'folder', 'permission' => 'faq-categories.index'],
                    ['name' => __('FAQs'), 'route' => 'faqs.index', 'icon' => 'question-mark-circle', 'permission' => 'faqs.index'],
                ],
            ],
            'content' => [
                'title' => __('Content'),
                'modules' => [
                    ['name' => __('Content Items'), 'route' => 'content-items.index', 'icon' => 'document-text', 'permission' => 'content-items.index'],
                    ['name' => __('Content Folders'), 'route' => 'content-folders.index', 'icon' => 'folder', 'permission' => 'content-folders.index'],
                    ['name' => __('Content Types'), 'route' => 'content-types.index', 'icon' => 'rectangle-stack', 'permission' => 'content-types.index'],
                ],
            ],
            'financial' => [
                'title' => __('Financieel'),
                'modules' => [
                    ['name' => __('Facturen'), 'route' => 'invoices.index', 'icon' => 'document-text', 'permission' => 'invoices.index'],
                    ['name' => __('Factuur Statussen'), 'route' => 'invoice-statuses.index', 'icon' => 'tag', 'permission' => 'invoice-statuses.index'],
                    ['name' => __('Invoice Taxes'), 'route' => 'invoice-taxes.index', 'icon' => 'calculator', 'permission' => 'invoice-taxes.index'],
                    ['name' => __('Orders'), 'route' => 'orders.index', 'icon' => 'shopping-cart', 'permission' => 'orders.index'],
                    ['name' => __('Order Statussen'), 'route' => 'order-statuses.index', 'icon' => 'tag', 'permission' => 'order-statuses.index'],
                ],
            ],
            'access_control' => [
                'title' => __('Access Control'),
                'modules' => [
                    ['name' => __('Users'), 'route' => 'users.index', 'icon' => 'users', 'permission' => 'users.index'],
                    ['name' => __('Roles'), 'route' => 'roles.index', 'icon' => 'shield-check', 'permission' => null],
                    ['name' => __('Permissions'), 'route' => 'permissions.index', 'icon' => 'key', 'permission' => null],
                ],
            ],
            'settings' => [
                'title' => __('Settings'),
                'modules' => [
                    ['name' => __('Settings'), 'route' => 'settings-items.index', 'icon' => 'cog-6-tooth', 'permission' => 'settings-items.index'],
                    ['name' => __('Categories'), 'route' => 'settings-categories.index', 'icon' => 'squares-2x2', 'permission' => 'settings-categories.index'],
                    ['name' => __('Field Types'), 'route' => 'settings-field-types.index', 'icon' => 'view-columns', 'permission' => 'settings-field-types.index'],
                ],
            ],
        ];
    }
}; ?>

<div class="space-y-8">
    <div>
        <flux:heading size="xl">{{ __('Modules') }}</flux:heading>
        <flux:subheading>{{ __('Access all available modules') }}</flux:subheading>
    </div>

    @foreach($this->modules() as $group)
        <div>
            <flux:heading size="lg" class="mb-4">{{ $group['title'] }}</flux:heading>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($group['modules'] as $module)
                    @can($module['permission'] ?? 'nonexistent')
                        <a
                            href="{{ route($module['route']) }}"
                            wire:navigate
                            class="group relative overflow-hidden rounded-lg border border-zinc-200 bg-white p-6 transition-all hover:border-zinc-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600"
                        >
                            <div class="flex items-start gap-4">
                                <div class="rounded-lg bg-zinc-100 p-3 text-zinc-600 transition-colors group-hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:group-hover:bg-zinc-700">
                                    <flux:icon.{{ $module['icon'] }} class="size-6" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $module['name'] }}</h3>
                                </div>
                            </div>
                        </a>
                    @else
                        @if(!$module['permission'])
                            <a
                                href="{{ route($module['route']) }}"
                                wire:navigate
                                class="group relative overflow-hidden rounded-lg border border-zinc-200 bg-white p-6 transition-all hover:border-zinc-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600"
                            >
                                <div class="flex items-start gap-4">
                                    <div class="rounded-lg bg-zinc-100 p-3 text-zinc-600 transition-colors group-hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:group-hover:bg-zinc-700">
                                        <flux:icon.{{ $module['icon'] }} class="size-6" />
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $module['name'] }}</h3>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endcan
                @endforeach
            </div>
        </div>
    @endforeach
</div>
