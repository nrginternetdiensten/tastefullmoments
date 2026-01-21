<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/shipping-items', function () {
        return view('pages.shipping-items.index');
    })->middleware('can:shipping-items.index')->name('shipping-items.index');

    Volt::route('/shipping-items/create', 'shipping-items.form')
        ->name('shipping-items.create')
        ->middleware('can:shipping-items.create');

    Volt::route('/shipping-items/{shippingItem}/edit', 'shipping-items.form')
        ->name('shipping-items.edit')
        ->middleware('can:shipping-items.edit');
});
