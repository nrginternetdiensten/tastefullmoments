<?php

declare(strict_types=1);

use App\Models\TicketChannel;
use App\Models\User;
use Livewire\Livewire;

test('users can view ticket channels index', function () {
    $user = User::factory()->create();
    TicketChannel::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('ticket-channels.index'))
        ->assertOk()
        ->assertSeeLivewire('ticket-channels.index');
});

test('users can create a ticket channel', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-channels.form')
        ->set('name', 'Email')
        ->set('class', 'bg-blue-500')
        ->set('list_order', '10')
        ->set('active', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('ticket-channels.index'));

    expect(TicketChannel::where('name', 'Email')->exists())->toBeTrue();
});

test('users can edit a ticket channel', function () {
    $user = User::factory()->create();
    $ticketChannel = TicketChannel::factory()->create([
        'name' => 'Old Channel',
        'list_order' => 5,
    ]);

    Livewire::actingAs($user)
        ->test('ticket-channels.form', ['ticketChannel' => $ticketChannel])
        ->assertSet('name', 'Old Channel')
        ->assertSet('list_order', '5')
        ->set('name', 'Updated Channel')
        ->set('list_order', '15')
        ->call('save')
        ->assertHasNoErrors();

    $ticketChannel->refresh();
    expect($ticketChannel->name)->toBe('Updated Channel');
    expect($ticketChannel->list_order)->toBe(15);
});

test('users can delete a ticket channel', function () {
    $user = User::factory()->create();
    $ticketChannel = TicketChannel::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-channels.index')
        ->call('delete', $ticketChannel->id);

    expect(TicketChannel::find($ticketChannel->id))->toBeNull();
});

test('ticket channel name is required', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('ticket-channels.form')
        ->set('name', '')
        ->set('list_order', '0')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('users can search ticket channels', function () {
    $user = User::factory()->create();
    TicketChannel::factory()->create(['name' => 'Email']);
    TicketChannel::factory()->create(['name' => 'Phone']);
    TicketChannel::factory()->create(['name' => 'Chat']);

    Livewire::actingAs($user)
        ->test('ticket-channels.index')
        ->set('search', 'Email')
        ->assertSee('Email')
        ->assertDontSee('Phone');
});

test('ticket channels are sorted by list_order by default', function () {
    $user = User::factory()->create();
    TicketChannel::factory()->create(['name' => 'Third', 'list_order' => 30]);
    TicketChannel::factory()->create(['name' => 'First', 'list_order' => 10]);
    TicketChannel::factory()->create(['name' => 'Second', 'list_order' => 20]);

    $this->actingAs($user)
        ->get(route('ticket-channels.index'))
        ->assertSeeInOrder(['First', 'Second', 'Third']);
});
