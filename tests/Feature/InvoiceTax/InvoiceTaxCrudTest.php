<?php

declare(strict_types=1);

use App\Models\InvoiceTax;
use App\Models\User;
use Livewire\Livewire;

test('users can view invoice taxes index', function () {
    $user = User::factory()->create();
    InvoiceTax::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('invoice-taxes.index'))
        ->assertOk()
        ->assertSeeLivewire('invoice-taxes.index');
});

test('users can create an invoice tax', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('invoice-taxes.form')
        ->set('name', 'VAT')
        ->set('percentage', '21.00')
        ->set('active', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('invoice-taxes.index'));

    expect(InvoiceTax::where('name', 'VAT')->exists())->toBeTrue();
});

test('users can edit an invoice tax', function () {
    $user = User::factory()->create();
    $invoiceTax = InvoiceTax::factory()->create([
        'name' => 'Old Tax',
        'percentage' => 10.00,
    ]);

    Livewire::actingAs($user)
        ->test('invoice-taxes.form', ['invoiceTax' => $invoiceTax])
        ->assertSet('name', 'Old Tax')
        ->assertSet('percentage', '10.00')
        ->set('name', 'Updated Tax')
        ->set('percentage', '15.50')
        ->call('save')
        ->assertHasNoErrors();

    $invoiceTax->refresh();
    expect($invoiceTax->name)->toBe('Updated Tax');
    expect((string) $invoiceTax->percentage)->toBe('15.50');
});

test('users can delete an invoice tax', function () {
    $user = User::factory()->create();
    $invoiceTax = InvoiceTax::factory()->create();

    Livewire::actingAs($user)
        ->test('invoice-taxes.index')
        ->call('delete', $invoiceTax->id);

    expect(InvoiceTax::find($invoiceTax->id))->toBeNull();
});

test('invoice tax name is required', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('invoice-taxes.form')
        ->set('name', '')
        ->set('percentage', '21.00')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('invoice tax percentage is required and must be numeric', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('invoice-taxes.form')
        ->set('name', 'VAT')
        ->set('percentage', '')
        ->call('save')
        ->assertHasErrors(['percentage' => 'required']);

    Livewire::actingAs($user)
        ->test('invoice-taxes.form')
        ->set('name', 'VAT')
        ->set('percentage', 'not a number')
        ->call('save')
        ->assertHasErrors(['percentage' => 'numeric']);
});

test('invoice tax percentage must be between 0 and 100', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('invoice-taxes.form')
        ->set('name', 'VAT')
        ->set('percentage', '-5')
        ->call('save')
        ->assertHasErrors(['percentage' => 'min']);

    Livewire::actingAs($user)
        ->test('invoice-taxes.form')
        ->set('name', 'VAT')
        ->set('percentage', '150')
        ->call('save')
        ->assertHasErrors(['percentage' => 'max']);
});

test('users can search invoice taxes', function () {
    $user = User::factory()->create();
    InvoiceTax::factory()->create(['name' => 'VAT']);
    InvoiceTax::factory()->create(['name' => 'GST']);
    InvoiceTax::factory()->create(['name' => 'Sales Tax']);

    Livewire::actingAs($user)
        ->test('invoice-taxes.index')
        ->set('search', 'VAT')
        ->assertSee('VAT')
        ->assertDontSee('GST')
        ->assertDontSee('Sales Tax');
});
