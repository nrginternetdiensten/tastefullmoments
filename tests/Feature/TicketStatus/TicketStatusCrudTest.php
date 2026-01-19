<?php

declare(strict_types=1);

use App\Models\TicketStatus;
use App\Models\User;
use Livewire\Livewire;

test('users can view ticket statuses index', function () {
    $user = User::factory()->create();
    TicketStatus::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('ticket-statuses.index'))
        ->assertOk()
        ->assertSeeLivewire('ticket-statuses.index');
});

test('users can create a ticket status', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-statuses.form')
        ->set('name', 'Open')
        ->set('class', 'bg-blue-500')
        ->set('list_order', '10')
        ->set('active', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('ticket-statuses.index'));

    expect(TicketStatus::where('name', 'Open')->exists())->toBeTrue();
});

test('users can edit a ticket status', function () {
    $user = User::factory()->create();
    $ticketStatus = TicketStatus::factory()->create([
        'name' => 'Old Status',
        'list_order' => 5,
    ]);

    Livewire::actingAs($user)
        ->test('ticket-statuses.form', ['ticketStatus' => $ticketStatus])
        ->assertSet('name', 'Old Status')
        ->assertSet('list_order', '5')
        ->set('name', 'Updated Status')
        ->set('list_order', '15')
        ->call('save')
        ->assertHasNoErrors();

    $ticketStatus->refresh();
    expect($ticketStatus->name)->toBe('Updated Status');
    expect($ticketStatus->list_order)->toBe(15);
});

test('users can delete a ticket status', function () {
    $user = User::factory()->create();
    $ticketStatus = TicketStatus::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-statuses.index')
        ->call('delete', $ticketStatus->id);

    expect(TicketStatus::find($ticketStatus->id))->toBeNull();
});

test('ticket status name is required', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-statuses.form')
        ->set('name', '')
        ->set('list_order', '0')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('ticket status list_order is required and must be integer', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-statuses.form')
        ->set('name', 'Open')
        ->set('list_order', '')
        ->call('save')
        ->assertHasErrors(['list_order' => 'required']);

    Livewire::actingAs($user)
        ->test('ticket-statuses.form')
        ->set('name', 'Open')
        ->set('list_order', 'not a number')
        ->call('save')
        ->assertHasErrors(['list_order' => 'integer']);
});

test('ticket status list_order must be at least 0', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-statuses.form')
        ->set('name', 'Open')
        ->set('list_order', '-5')
        ->call('save')
        ->assertHasErrors(['list_order' => 'min']);
});

test('users can search ticket statuses', function () {
    $user = User::factory()->create();
    TicketStatus::factory()->create(['name' => 'Open']);
    TicketStatus::factory()->create(['name' => 'Closed']);
    TicketStatus::factory()->create(['name' => 'In Progress']);

    Livewire::actingAs($user)
        ->test('ticket-statuses.index')
        ->set('search', 'Open')
        ->assertSee('Open')
        ->assertDontSee('Closed');
});

test('ticket statuses are sorted by list_order by default', function () {
    $user = User::factory()->create();
    TicketStatus::factory()->create(['name' => 'Third', 'list_order' => 30]);
    TicketStatus::factory()->create(['name' => 'First', 'list_order' => 10]);
    TicketStatus::factory()->create(['name' => 'Second', 'list_order' => 20]);

    $this->actingAs($user)
        ->get(route('ticket-statuses.index'))
        ->assertSeeInOrder(['First', 'Second', 'Third']);
});
