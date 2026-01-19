<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('accounts', function () {
        return view('pages.accounts.index');
    })->middleware('can:accounts.index')->name('accounts.index');

    Route::get('accounts/create', function () {
        return view('pages.accounts.create');
    })->middleware('can:accounts.create')->name('accounts.create');

    Route::get('accounts/{account}/edit', function (\App\Models\Account $account) {
        return view('pages.accounts.edit', ['account' => $account]);
    })->middleware('can:accounts.edit')->name('accounts.edit');

    // Roles
    Route::get('roles', function () {
        return view('pages.roles.index');
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
});

require __DIR__.'/settings.php';
