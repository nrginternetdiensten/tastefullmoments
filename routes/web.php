<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// ============================================================================
// FRONTEND ROUTES (Public)
// ============================================================================

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::get('/about', function () {
    return view('frontend.about');
})->name('frontend.about');

Route::get('/contact', function () {
    return view('frontend.contact');
})->name('frontend.contact');

Route::get('/faq', function () {
    return view('frontend.faq');
})->name('frontend.faq');

// Content pages - SEO friendly URLs
Route::get('/c-{seoUrl}.html', [App\Http\Controllers\Frontend\ContentController::class, 'show'])->name('frontend.content');

// ============================================================================
// ACCOUNT ROUTES (Authenticated Frontend Users)
// ============================================================================

Route::middleware(['auth', 'verified'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [App\Http\Controllers\FrontAccountController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [App\Http\Controllers\FrontAccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\FrontAccountController::class, 'updateProfile'])->name('profile.update');

    Route::get('/password', [App\Http\Controllers\FrontAccountController::class, 'password'])->name('password');
    Route::put('/password', [App\Http\Controllers\FrontAccountController::class, 'updatePassword'])->name('password.update');

    Route::get('/email', [App\Http\Controllers\FrontAccountController::class, 'email'])->name('email');
    Route::put('/email', [App\Http\Controllers\FrontAccountController::class, 'updateEmail'])->name('email.update');

    // Tickets
    Route::get('/tickets', function () {
        return view('frontend.account.tickets.index');
    })->name('tickets.index');

    Route::get('/tickets/create', function () {
        return view('frontend.account.tickets.create');
    })->name('tickets.create');

    Route::get('/tickets/{ticket}', function (\App\Models\Ticket $ticket) {
        return view('frontend.account.tickets.show', ['ticket' => $ticket]);
    })->name('tickets.show');

    Route::post('/logout', [App\Http\Controllers\FrontAccountController::class, 'logout'])->name('logout');
});

// ============================================================================
// ADMIN ROUTES (Dashboard & Management)
// ============================================================================

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('modules', function () {
        return view('pages.modules.index');
    })->name('modules.index');

    // Accounts Management
    Route::get('accounts', function () {
        return view('pages.accounts.index');
    })->middleware('can:accounts.index')->name('accounts.index');

    Route::get('accounts/create', function () {
        return view('pages.accounts.create');
    })->middleware('can:accounts.create')->name('accounts.create');

    Route::get('accounts/{account}/edit', function (\App\Models\Account $account) {
        return view('pages.accounts.edit', ['account' => $account]);
    })->middleware('can:accounts.edit')->name('accounts.edit');

    // Account Types
    Route::get('account-types', function () {
        return view('pages.account-types.index');
    })->middleware('can:account-types.index')->name('account-types.index');

    Route::get('account-types/create', function () {
        return view('pages.account-types.create');
    })->middleware('can:account-types.create')->name('account-types.create');

    Route::get('account-types/{accountType}/edit', function (\App\Models\AccountType $accountType) {
        return view('pages.account-types.edit', ['accountType' => $accountType]);
    })->middleware('can:account-types.edit')->name('account-types.edit');

    // Account Transactions
    Volt::route('account-transactions', 'pages.account-transactions.index')->name('account-transactions.index');
    Volt::route('account-transactions/create', 'pages.account-transactions.create')->name('account-transactions.create');
    Volt::route('account-transactions/{transaction}/edit', 'pages.account-transactions.edit')->name('account-transactions.edit');

    // Roles & Permissions
    Route::get('roles', function () {
        return view('pages.roles.index');
    })->name('roles.index');

    Route::get('roles/create', function () {
        return view('pages.roles.create');
    })->name('roles.create');

    Route::get('roles/{role}/edit', function (\Spatie\Permission\Models\Role $role) {
        return view('pages.roles.edit', ['role' => $role]);
    })->name('roles.edit');

    Route::get('permissions', function () {
        return view('pages.permissions.index');
    })->name('permissions.index');

    Route::get('permissions/create', function () {
        return view('pages.permissions.create');
    })->name('permissions.create');

    Route::get('permissions/{permission}/edit', function (\Spatie\Permission\Models\Permission $permission) {
        return view('pages.permissions.edit', ['permission' => $permission]);
    })->name('permissions.edit');

    // Users
    Route::get('users', function () {
        return view('pages.users.index');
    })->middleware('can:users.index')->name('users.index');

    Route::get('users/create', function () {
        return view('pages.users.create');
    })->middleware('can:users.create')->name('users.create');

    Route::get('users/{user}/edit', function (\App\Models\User $user) {
        return view('pages.users.edit', ['user' => $user]);
    })->middleware('can:users.edit')->name('users.edit');

    Route::get('users/{user}/edit-role', function (\App\Models\User $user) {
        return view('pages.users.edit-role', ['user' => $user]);
    })->middleware('can:users.edit')->name('users.edit-role');

    // Leads
    Route::get('lead-statuses', function () {
        return view('pages.lead-statuses.index');
    })->name('lead-statuses.index');

    Route::get('lead-statuses/create', function () {
        return view('pages.lead-statuses.create');
    })->name('lead-statuses.create');

    Route::get('lead-statuses/{leadStatus}/edit', function (\App\Models\LeadStatus $leadStatus) {
        return view('pages.lead-statuses.edit', ['leadStatus' => $leadStatus]);
    })->name('lead-statuses.edit');

    Route::get('lead-categories', function () {
        return view('pages.lead-categories.index');
    })->name('lead-categories.index');

    Route::get('lead-categories/create', function () {
        return view('pages.lead-categories.create');
    })->name('lead-categories.create');

    Route::get('lead-categories/{leadCategory}/edit', function (\App\Models\LeadCategory $leadCategory) {
        return view('pages.lead-categories.edit', ['leadCategory' => $leadCategory]);
    })->name('lead-categories.edit');

    Route::get('lead-channels', function () {
        return view('pages.lead-channels.index');
    })->name('lead-channels.index');

    Route::get('lead-channels/create', function () {
        return view('pages.lead-channels.create');
    })->name('lead-channels.create');

    Route::get('lead-channels/{leadChannel}/edit', function (\App\Models\LeadChannel $leadChannel) {
        return view('pages.lead-channels.edit', ['leadChannel' => $leadChannel]);
    })->name('lead-channels.edit');

    Route::get('lead-items', function () {
        return view('pages.lead-items.index');
    })->name('lead-items.index');

    Route::get('lead-items/create', function () {
        return view('pages.lead-items.create');
    })->name('lead-items.create');

    Route::get('lead-items/{leadItem}/edit', function (\App\Models\LeadItem $leadItem) {
        return view('pages.lead-items.edit', ['leadItem' => $leadItem]);
    })->name('lead-items.edit');

    // Content Management
    Volt::route('content-types', 'pages.content-types.index')->name('content-types.index');
    Volt::route('content-types/create', 'pages.content-types.create')->name('content-types.create');
    Volt::route('content-types/{contentType}/edit', 'pages.content-types.edit')->name('content-types.edit');

    Volt::route('content-folders', 'pages.content-folders.index')->name('content-folders.index');
    Volt::route('content-folders/create', 'pages.content-folders.create')->name('content-folders.create');
    Volt::route('content-folders/{contentFolder}/edit', 'pages.content-folders.edit')->name('content-folders.edit');

    Volt::route('content-items', 'pages.content-items.index')->name('content-items.index');
    Volt::route('content-items/create', 'pages.content-items.create')->name('content-items.create');
    Volt::route('content-items/{contentItem}/edit', 'pages.content-items.edit')->name('content-items.edit');

    Volt::route('blog-categories', 'pages.blog-categories.index')->name('blog-categories.index');
    Volt::route('blog-categories/create', 'pages.blog-categories.create')->name('blog-categories.create');
    Volt::route('blog-categories/{blogCategory}/edit', 'pages.blog-categories.edit')->name('blog-categories.edit');

    // Email Management
    Route::get('email-folders', function () {
        return view('pages.email-folders.index');
    })->name('email-folders.index');

    Route::get('email-folders/create', function () {
        return view('pages.email-folders.create');
    })->name('email-folders.create');

    Route::get('email-folders/{emailFolder}/edit', function (\App\Models\EmailFolder $emailFolder) {
        return view('pages.email-folders.edit', ['emailFolder' => $emailFolder]);
    })->name('email-folders.edit');

    Route::get('email-items', function () {
        return view('pages.email-items.index');
    })->name('email-items.index');

    Route::get('email-items/create', function () {
        return view('pages.email-items.create');
    })->name('email-items.create');

    Route::get('email-items/{emailItem}/edit', function (\App\Models\EmailItem $emailItem) {
        return view('pages.email-items.edit', ['emailItem' => $emailItem]);
    })->name('email-items.edit');

    // FAQ Management
    Route::get('faq-categories', function () {
        return view('pages.faq-categories.index');
    })->middleware('can:faq-categories.index')->name('faq-categories.index');

    Route::get('faq-categories/create', function () {
        return view('pages.faq-categories.create');
    })->middleware('can:faq-categories.create')->name('faq-categories.create');

    Route::get('faq-categories/{faqCategory}/edit', function (\App\Models\FaqCategory $faqCategory) {
        return view('pages.faq-categories.edit', ['faqCategory' => $faqCategory]);
    })->middleware('can:faq-categories.edit')->name('faq-categories.edit');

    Route::get('faqs', function () {
        return view('pages.faqs.index');
    })->middleware('can:faqs.index')->name('faqs.index');

    Route::get('faqs/create', function () {
        return view('pages.faqs.create');
    })->middleware('can:faqs.create')->name('faqs.create');

    Route::get('faqs/{faq}/edit', function (\App\Models\Faq $faq) {
        return view('pages.faqs.edit', ['faq' => $faq]);
    })->middleware('can:faqs.edit')->name('faqs.edit');

    // Invoices
    Route::get('invoice-statuses', function () {
        return view('pages.invoice-statuses.index');
    })->middleware('can:invoice-statuses.index')->name('invoice-statuses.index');

    Route::get('invoice-statuses/create', function () {
        return view('pages.invoice-statuses.create');
    })->middleware('can:invoice-statuses.create')->name('invoice-statuses.create');

    Route::get('invoice-statuses/{invoiceStatus}/edit', function (\App\Models\InvoiceStatus $invoiceStatus) {
        return view('pages.invoice-statuses.edit', ['invoiceStatus' => $invoiceStatus]);
    })->middleware('can:invoice-statuses.edit')->name('invoice-statuses.edit');

    Route::get('invoice-taxes', function () {
        return view('pages.invoice-taxes.index');
    })->name('invoice-taxes.index');

    Route::get('invoice-taxes/create', function () {
        return view('pages.invoice-taxes.create');
    })->name('invoice-taxes.create');

    Route::get('invoice-taxes/{invoiceTax}/edit', function (\App\Models\InvoiceTax $invoiceTax) {
        return view('pages.invoice-taxes.edit', ['invoiceTax' => $invoiceTax]);
    })->name('invoice-taxes.edit');

    Route::get('invoices', function () {
        return view('pages.invoices.index');
    })->middleware('can:invoices.index')->name('invoices.index');

    Route::get('invoices/create', function () {
        return view('pages.invoices.create');
    })->middleware('can:invoices.create')->name('invoices.create');

    Route::get('invoices/{invoice}/edit', function (\App\Models\Invoice $invoice) {
        return view('pages.invoices.edit', ['invoice' => $invoice]);
    })->middleware('can:invoices.edit')->name('invoices.edit');

    Route::get('invoices/{invoice}/download', function (\App\Models\Invoice $invoice) {
        return $invoice->downloadPdf();
    })->middleware('can:invoices.index')->name('invoices.download');

    // Orders
    Route::get('order-statuses', function () {
        return view('pages.order-statuses.index');
    })->middleware('can:order-statuses.index')->name('order-statuses.index');

    Route::get('order-statuses/create', function () {
        return view('pages.order-statuses.create');
    })->middleware('can:order-statuses.create')->name('order-statuses.create');

    Route::get('order-statuses/{orderStatus}/edit', function (\App\Models\OrderStatus $orderStatus) {
        return view('pages.order-statuses.edit', ['orderStatus' => $orderStatus]);
    })->middleware('can:order-statuses.edit')->name('order-statuses.edit');

    Route::get('orders', function () {
        return view('pages.orders.index');
    })->middleware('can:orders.index')->name('orders.index');

    Route::get('orders/create', function () {
        return view('pages.orders.create');
    })->middleware('can:orders.create')->name('orders.create');

    Route::get('orders/{order}/edit', function (\App\Models\Order $order) {
        return view('pages.orders.edit', ['order' => $order]);
    })->middleware('can:orders.edit')->name('orders.edit');

    // Tickets (Admin)
    Route::get('tickets', function () {
        return view('pages.tickets.index');
    })->name('tickets.index');

    Route::get('tickets/create', function () {
        return view('pages.tickets.create');
    })->name('tickets.create');

    Route::get('tickets/{ticket}/edit', function (\App\Models\Ticket $ticket) {
        return view('pages.tickets.edit', ['ticket' => $ticket]);
    })->name('tickets.edit');

    Route::get('ticket-statuses', function () {
        return view('pages.ticket-statuses.index');
    })->name('ticket-statuses.index');

    Route::get('ticket-statuses/create', function () {
        return view('pages.ticket-statuses.create');
    })->name('ticket-statuses.create');

    Route::get('ticket-statuses/{ticketStatus}/edit', function (\App\Models\TicketStatus $ticketStatus) {
        return view('pages.ticket-statuses.edit', ['ticketStatus' => $ticketStatus]);
    })->name('ticket-statuses.edit');

    Route::get('ticket-channels', function () {
        return view('pages.ticket-channels.index');
    })->name('ticket-channels.index');

    Route::get('ticket-channels/create', function () {
        return view('pages.ticket-channels.create');
    })->name('ticket-channels.create');

    Route::get('ticket-channels/{ticketChannel}/edit', function (\App\Models\TicketChannel $ticketChannel) {
        return view('pages.ticket-channels.edit', ['ticketChannel' => $ticketChannel]);
    })->name('ticket-channels.edit');

    Route::get('ticket-types', function () {
        return view('pages.ticket-types.index');
    })->name('ticket-types.index');

    Route::get('ticket-types/create', function () {
        return view('pages.ticket-types.create');
    })->name('ticket-types.create');

    Route::get('ticket-types/{ticketType}/edit', function (\App\Models\TicketType $ticketType) {
        return view('pages.ticket-types.edit', ['ticketType' => $ticketType]);
    })->name('ticket-types.edit');

    // Shipping
    Volt::route('shipping-items', 'shipping-items.index')
        ->middleware('can:shipping-items.index')
        ->name('shipping-items.index');

    Volt::route('shipping-items/create', 'shipping-items.form')
        ->middleware('can:shipping-items.create')
        ->name('shipping-items.create');

    Volt::route('shipping-items/{shippingItem}/edit', 'shipping-items.form')
        ->middleware('can:shipping-items.edit')
        ->name('shipping-items.edit');

    // Settings
    Route::get('settings-categories', function () {
        return view('pages.settings-categories.index');
    })->name('settings-categories.index');

    Route::get('settings-categories/create', function () {
        return view('pages.settings-categories.create');
    })->name('settings-categories.create');

    Route::get('settings-categories/{settingsCategory}/edit', function (\App\Models\SettingsCategory $settingsCategory) {
        return view('pages.settings-categories.edit', ['settingsCategory' => $settingsCategory]);
    })->name('settings-categories.edit');

    Route::get('settings-field-types', function () {
        return view('pages.settings-field-types.index');
    })->name('settings-field-types.index');

    Route::get('settings-field-types/create', function () {
        return view('pages.settings-field-types.create');
    })->name('settings-field-types.create');

    Route::get('settings-field-types/{settingsFieldType}/edit', function (\App\Models\SettingsFieldType $settingsFieldType) {
        return view('pages.settings-field-types.edit', ['settingsFieldType' => $settingsFieldType]);
    })->name('settings-field-types.edit');

    Route::get('settings-items', function () {
        return view('pages.settings-items.create');
    })->name('roles.index');

    Route::get('roles/create', function () {
        return view('pages.roles.create');
    })->name('roles.create');

    Route::get('roles/{role}/edit', function (\Spatie\Permission\Models\Role $role) {
        return view('pages.roles.edit', ['role' => $role]);
    })->name('roles.edit');

    // Permissions
    Route::get('permissions', function () {
        return view('pages.permissions.index');
    })->name('permissions.index');

    Route::get('permissions/create', function () {
        return view('pages.permissions.create');
    })->name('permissions.create');

    Route::get('permissions/{permission}/edit', function (\Spatie\Permission\Models\Permission $permission) {
        return view('pages.permissions.edit', ['permission' => $permission]);
    })->name('permissions.edit');

    // Lead Statuses
    Route::get('lead-statuses', function () {
        return view('pages.lead-statuses.index');
    })->name('lead-statuses.index');

    Route::get('lead-statuses/create', function () {
        return view('pages.lead-statuses.create');
    })->name('lead-statuses.create');

    Route::get('lead-statuses/{leadStatus}/edit', function (\App\Models\LeadStatus $leadStatus) {
        return view('pages.lead-statuses.edit', ['leadStatus' => $leadStatus]);
    })->name('lead-statuses.edit');

    // Lead Categories
    Route::get('lead-categories', function () {
        return view('pages.lead-categories.index');
    })->name('lead-categories.index');

    Route::get('lead-categories/create', function () {
        return view('pages.lead-categories.create');
    })->name('lead-categories.create');

    Route::get('lead-categories/{leadCategory}/edit', function (\App\Models\LeadCategory $leadCategory) {
        return view('pages.lead-categories.edit', ['leadCategory' => $leadCategory]);
    })->name('lead-categories.edit');

    // Lead Channels
    Route::get('lead-channels', function () {
        return view('pages.lead-channels.index');
    })->name('lead-channels.index');

    Route::get('lead-channels/create', function () {
        return view('pages.lead-channels.create');
    })->name('lead-channels.create');

    Route::get('lead-channels/{leadChannel}/edit', function (\App\Models\LeadChannel $leadChannel) {
        return view('pages.lead-channels.edit', ['leadChannel' => $leadChannel]);
    })->name('lead-channels.edit');

    // Content Types
    Volt::route('content-types', 'pages.content-types.index')->name('content-types.index');
    Volt::route('content-types/create', 'pages.content-types.create')->name('content-types.create');
    Volt::route('content-types/{contentType}/edit', 'pages.content-types.edit')->name('content-types.edit');

    // Content Folders
    Volt::route('content-folders', 'pages.content-folders.index')->name('content-folders.index');
    Volt::route('content-folders/create', 'pages.content-folders.create')->name('content-folders.create');
    Volt::route('content-folders/{contentFolder}/edit', 'pages.content-folders.edit')->name('content-folders.edit');

    // Content Items
    Volt::route('content-items', 'pages.content-items.index')->name('content-items.index');
    Volt::route('content-items/create', 'pages.content-items.create')->name('content-items.create');
    Volt::route('content-items/{contentItem}/edit', 'pages.content-items.edit')->name('content-items.edit');

    // Blog Categories
    Volt::route('blog-categories', 'pages.blog-categories.index')->name('blog-categories.index');
    Volt::route('blog-categories/create', 'pages.blog-categories.create')->name('blog-categories.create');
    Volt::route('blog-categories/{blogCategory}/edit', 'pages.blog-categories.edit')->name('blog-categories.edit');

    // Lead Items
    Route::get('lead-items', function () {
        return view('pages.lead-items.index');
    })->name('lead-items.index');

    Route::get('lead-items/create', function () {
        return view('pages.lead-items.create');
    })->name('lead-items.create');

    Route::get('lead-items/{leadItem}/edit', function (\App\Models\LeadItem $leadItem) {
        return view('pages.lead-items.edit', ['leadItem' => $leadItem]);
    })->name('lead-items.edit');

    // FAQ Categories
    Route::get('faq-categories', function () {
        return view('pages.faq-categories.index');
    })->middleware('can:faq-categories.index')->name('faq-categories.index');

    Route::get('faq-categories/create', function () {
        return view('pages.faq-categories.create');
    })->middleware('can:faq-categories.create')->name('faq-categories.create');

    Route::get('faq-categories/{faqCategory}/edit', function (\App\Models\FaqCategory $faqCategory) {
        return view('pages.faq-categories.edit', ['faqCategory' => $faqCategory]);
    })->middleware('can:faq-categories.edit')->name('faq-categories.edit');

    // FAQs
    Route::get('faqs', function () {
        return view('pages.faqs.index');
    })->middleware('can:faqs.index')->name('faqs.index');

    Route::get('faqs/create', function () {
        return view('pages.faqs.create');
    })->middleware('can:faqs.create')->name('faqs.create');

    Route::get('faqs/{faq}/edit', function (\App\Models\Faq $faq) {
        return view('pages.faqs.edit', ['faq' => $faq]);
    })->middleware('can:faqs.edit')->name('faqs.edit');

    // Settings Categories
    Route::get('settings-categories', function () {
        return view('pages.settings-categories.index');
    })->name('settings-categories.index');

    Route::get('settings-categories/create', function () {
        return view('pages.settings-categories.create');
    })->name('settings-categories.create');

    Route::get('settings-categories/{settingsCategory}/edit', function (\App\Models\SettingsCategory $settingsCategory) {
        return view('pages.settings-categories.edit', ['settingsCategory' => $settingsCategory]);
    })->name('settings-categories.edit');

    // Settings Field Types
    Route::get('settings-field-types', function () {
        return view('pages.settings-field-types.index');
    })->name('settings-field-types.index');

    Route::get('settings-field-types/create', function () {
        return view('pages.settings-field-types.create');
    })->name('settings-field-types.create');

    Route::get('settings-field-types/{settingsFieldType}/edit', function (\App\Models\SettingsFieldType $settingsFieldType) {
        return view('pages.settings-field-types.edit', ['settingsFieldType' => $settingsFieldType]);
    })->name('settings-field-types.edit');

    // Settings Items
    Route::get('settings-items', function () {
        return view('pages.settings-items.index');
    })->name('settings-items.index');

    Route::get('settings-items/create', function () {
        return view('pages.settings-items.create');
    })->name('settings-items.create');

    Route::get('settings-items/{settingsItem}/edit', function (\App\Models\SettingsItem $settingsItem) {
        return view('pages.settings-items.edit', ['settingsItem' => $settingsItem]);
    })->name('settings-items.edit');

    // Shipping Items
    Volt::route('/shipping-items', 'shipping-items.index')
        ->middleware('can:shipping-items.index')
        ->name('shipping-items.index');

    Volt::route('/shipping-items/create', 'shipping-items.form')
        ->middleware('can:shipping-items.create')
        ->name('shipping-items.create');

    Volt::route('/shipping-items/{shippingItem}/edit', 'shipping-items.form')
        ->middleware('can:shipping-items.edit')
        ->name('shipping-items.edit');

    // Users
    Route::get('users', function () {
        return view('pages.users.index');
    })->middleware('can:users.index')->name('users.index');

    Route::get('users/create', function () {
        return view('pages.users.create');
    })->middleware('can:users.create')->name('users.create');

    Route::get('users/{user}/edit', function (\App\Models\User $user) {
        return view('pages.users.edit', ['user' => $user]);
    })->middleware('can:users.edit')->name('users.edit');

    Route::get('users/{user}/edit-role', function (\App\Models\User $user) {
        return view('pages.users.edit-role', ['user' => $user]);
    })->middleware('can:users.edit')->name('users.edit-role');

    // Email Folders
    Route::get('email-folders', function () {
        return view('pages.email-folders.index');
    })->name('email-folders.index');

    Route::get('email-folders/create', function () {
        return view('pages.email-folders.create');
    })->name('email-folders.create');

    Route::get('email-folders/{emailFolder}/edit', function (\App\Models\EmailFolder $emailFolder) {
        return view('pages.email-folders.edit', ['emailFolder' => $emailFolder]);
    })->name('email-folders.edit');

    // Email Items
    Route::get('email-items', function () {
        return view('pages.email-items.index');
    })->name('email-items.index');

    Route::get('email-items/create', function () {
        return view('pages.email-items.create');
    })->name('email-items.create');

    Route::get('email-items/{emailItem}/edit', function (\App\Models\EmailItem $emailItem) {
        return view('pages.email-items.edit', ['emailItem' => $emailItem]);
    })->name('email-items.edit');

    // Invoice Taxes
    Route::get('invoice-taxes', function () {
        return view('pages.invoice-taxes.index');
    })->name('invoice-taxes.index');

    Route::get('invoice-taxes/create', function () {
        return view('pages.invoice-taxes.create');
    })->name('invoice-taxes.create');

    Route::get('invoice-taxes/{invoiceTax}/edit', function (\App\Models\InvoiceTax $invoiceTax) {
        return view('pages.invoice-taxes.edit', ['invoiceTax' => $invoiceTax]);
    })->name('invoice-taxes.edit');

    // Ticket Statuses
    Route::get('ticket-statuses', function () {
        return view('pages.ticket-statuses.index');
    })->name('ticket-statuses.index');

    Route::get('ticket-statuses/create', function () {
        return view('pages.ticket-statuses.create');
    })->name('ticket-statuses.create');

    Route::get('ticket-statuses/{ticketStatus}/edit', function (\App\Models\TicketStatus $ticketStatus) {
        return view('pages.ticket-statuses.edit', ['ticketStatus' => $ticketStatus]);
    })->name('ticket-statuses.edit');

    // Ticket Channels
    Route::get('ticket-channels', function () {
        return view('pages.ticket-channels.index');
    })->name('ticket-channels.index');

    Route::get('ticket-channels/create', function () {
        return view('pages.ticket-channels.create');
    })->name('ticket-channels.create');

    Route::get('ticket-channels/{ticketChannel}/edit', function (\App\Models\TicketChannel $ticketChannel) {
        return view('pages.ticket-channels.edit', ['ticketChannel' => $ticketChannel]);
    })->name('ticket-channels.edit');

    // Ticket Types
    Route::get('ticket-types', function () {
        return view('pages.ticket-types.index');
    })->name('ticket-types.index');

    Route::get('ticket-types/create', function () {
        return view('pages.ticket-types.create');
    })->name('ticket-types.create');

    Route::get('ticket-types/{ticketType}/edit', function (\App\Models\TicketType $ticketType) {
        return view('pages.ticket-types.edit', ['ticketType' => $ticketType]);
    })->name('ticket-types.edit');

    // ColorScheme routes
    Route::get('color-schemes', function () {
        return view('pages.color-schemes.index');
    })->name('color-schemes.index');

    Route::get('color-schemes/create', function () {
        return view('pages.color-schemes.create');
    })->name('color-schemes.create');

    Route::get('color-schemes/{colorScheme}/edit', function (\App\Models\ColorScheme $colorScheme) {
        return view('pages.color-schemes.edit', ['colorScheme' => $colorScheme]);
    })->name('color-schemes.edit');

    // Tickets
    Route::get('tickets', function () {
        return view('pages.tickets.index');
    })->name('tickets.index');

    Route::get('tickets/create', function () {
        return view('pages.tickets.create');
    })->name('tickets.create');

    Route::get('tickets/{ticket}/edit', function (\App\Models\Ticket $ticket) {
        return view('pages.tickets.edit', ['ticket' => $ticket]);
    })->name('tickets.edit');

    // Invoice Statuses
    Route::get('invoice-statuses', function () {
        return view('pages.invoice-statuses.index');
    })->middleware('can:invoice-statuses.index')->name('invoice-statuses.index');

    Route::get('invoice-statuses/create', function () {
        return view('pages.invoice-statuses.create');
    })->middleware('can:invoice-statuses.create')->name('invoice-statuses.create');

    Route::get('invoice-statuses/{invoiceStatus}/edit', function (\App\Models\InvoiceStatus $invoiceStatus) {
        return view('pages.invoice-statuses.edit', ['invoiceStatus' => $invoiceStatus]);
    })->middleware('can:invoice-statuses.edit')->name('invoice-statuses.edit');

    // Invoices
    Route::get('invoices', function () {
        return view('pages.invoices.index');
    })->middleware('can:invoices.index')->name('invoices.index');

    Route::get('invoices/create', function () {
        return view('pages.invoices.create');
    })->middleware('can:invoices.create')->name('invoices.create');

    Route::get('invoices/{invoice}/edit', function (\App\Models\Invoice $invoice) {
        return view('pages.invoices.edit', ['invoice' => $invoice]);
    })->middleware('can:invoices.edit')->name('invoices.edit');

    Route::get('invoices/{invoice}/download', function (\App\Models\Invoice $invoice) {
        return $invoice->downloadPdf();
    })->middleware('can:invoices.index')->name('invoices.download');

    // Order Statuses
    Route::get('order-statuses', function () {
        return view('pages.order-statuses.index');
    })->middleware('can:order-statuses.index')->name('order-statuses.index');

    Route::get('order-statuses/create', function () {
        return view('pages.order-statuses.create');
    })->middleware('can:order-statuses.create')->name('order-statuses.create');

    Route::get('order-statuses/{orderStatus}/edit', function (\App\Models\OrderStatus $orderStatus) {
        return view('pages.order-statuses.edit', ['orderStatus' => $orderStatus]);
    })->middleware('can:order-statuses.edit')->name('order-statuses.edit');

    // Orders
    Route::get('orders', function () {
        return view('pages.orders.index');
    })->middleware('can:orders.index')->name('orders.index');

    Route::get('orders/create', function () {
        return view('pages.orders.create');
    })->middleware('can:orders.create')->name('orders.create');

    Route::get('orders/{order}/edit', function (\App\Models\Order $order) {
        return view('pages.orders.edit', ['order' => $order]);
    })->middleware('can:orders.edit')->name('orders.edit');
});

require __DIR__.'/settings.php';
