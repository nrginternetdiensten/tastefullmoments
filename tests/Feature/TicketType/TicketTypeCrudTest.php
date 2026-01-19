<?php

declare(strict_types=1);

use App\Models\TicketType;
use App\Models\User;
use Livewire\Livewire;

test('users can view ticket types index', function () {
    $user = User::factory()->create();
    TicketType::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('ticket-types.index'))
        ->assertOk()
        ->assertSeeLivewire('ticket-types.index');
});

test('users can create a ticket type', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-types.form')
        ->set('name', 'Bug')
        ->set('class', 'bg-red-500')
        ->set('list_order', '10')
        ->set('active', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('ticket-types.index'));

    expect(TicketType::where('name', 'Bug')->exists())->toBeTrue();
});

test('users can edit a ticket type', function () {
    $user = User::factory()->create();
    $ticketType = TicketType::factory()->create([
        'name' => 'Old Type',
        'list_order' => 5,
    ]);

    Livewire::actingAs($user)
        ->test('ticket-types.form', ['ticketType' => $ticketType])
        ->assertSet('name', 'Old Type')
        ->assertSet('list_order', '5')
        ->set('name', 'Updated Type')
        ->set('list_order', '15')
        ->call('save')
        ->assertHasNoErrors();

    $ticketType->refresh();
    expect($ticketType->name)->toBe('Updated Type');
    expect($ticketType->list_order)->toBe(15);
});

test('users can delete a ticket type', function () {
    $user = User::factory()->create();
    $ticketType = TicketType::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-types.index')
        ->call('delete', $ticketType->id);

    expect(TicketType::find($ticketType->id))->toBeNull();
});

test('ticket type name is required', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-types.form')
        ->set('name', '')
        ->set('list_order', '0')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('users can search ticket types', function () {
    $user = User::factory()->create();
    TicketType::factory()->create(['name' => 'Bug']);
    TicketType::factory()->create(['name' => 'Feature']);
    TicketType::factory()->create(['name' => 'Question']);

    Livewire::actingAs($user)
        ->test('ticket-types.index')
        ->set('search', 'Bug')
        ->assertSee('Bug')
        ->assertDontSee('Feature');
});

test('ticket types are sorted by list_order by default', function () {
    $user = User::factory()->create();
    TicketType::factory()->create(['name' => 'Third', 'list_order' => 30]);
    TicketType::factory()->create(['name' => 'First', 'list_order' => 10]);
    TicketType::factory()->create(['name' => 'Second', 'list_order' => 20]);

    $this->actingAs($user)
        ->get(route('ticket-types.index'))
        ->assertSeeInOrder(['First', 'Second', 'Third']);
});
