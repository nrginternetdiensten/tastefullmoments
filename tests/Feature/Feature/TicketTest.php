<?php

use App\Models\Account;
use App\Models\Ticket;
use App\Models\TicketChannel;
use App\Models\TicketStatus;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;

test('tickets index page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('tickets.index'));

    $response->assertOk();
});

test('tickets can be created', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();
    $channel = TicketChannel::factory()->create();
    $status = TicketStatus::factory()->create();
    $type = TicketType::factory()->create();

    $this->actingAs($user);

    Volt::test('tickets.form')
        ->set('title', 'Test Ticket')
        ->set('content', 'This is a test ticket description')
        ->set('account_id', (string) $account->id)
        ->set('user_id', (string) $user->id)
        ->set('channel_id', (string) $channel->id)
        ->set('status_id', (string) $status->id)
        ->set('type_id', (string) $type->id)
        ->call('save')
        ->assertHasNoErrors();

    expect(Ticket::where('title', 'Test Ticket')->exists())->toBeTrue();
});

test('tickets can be updated', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create([
        'title' => 'Original Title',
    ]);

    $this->actingAs($user);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->set('title', 'Updated Title')
        ->call('save')
        ->assertHasNoErrors();

    expect($ticket->fresh()->title)->toBe('Updated Title');
});

test('tickets can be deleted', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();

    $this->actingAs($user);

    Volt::test('tickets.index')
        ->call('delete', $ticket->id);

    expect(Ticket::find($ticket->id))->toBeNull();
});

test('tickets can be filtered by status type and channel', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();
    $channelEmail = TicketChannel::factory()->create(['name' => 'Email', 'active' => true]);
    $channelChat = TicketChannel::factory()->create(['name' => 'Chat', 'active' => true]);
    $statusOpen = TicketStatus::factory()->create(['name' => 'Open', 'active' => true]);
    $statusClosed = TicketStatus::factory()->create(['name' => 'Gesloten', 'active' => true]);
    $typeQuestion = TicketType::factory()->create(['name' => 'Vraag', 'active' => true]);
    $typeIncident = TicketType::factory()->create(['name' => 'Incident', 'active' => true]);

    $user->accounts()->sync([$account->id]);

    $matchingTicket = Ticket::factory()->create([
        'title' => 'Chat Incident Closed',
        'account_id' => $account->id,
        'user_id' => $user->id,
        'channel_id' => $channelChat->id,
        'status_id' => $statusClosed->id,
        'type_id' => $typeIncident->id,
    ]);

    Ticket::factory()->create([
        'title' => 'Email Question Open',
        'account_id' => $account->id,
        'user_id' => $user->id,
        'channel_id' => $channelEmail->id,
        'status_id' => $statusOpen->id,
        'type_id' => $typeQuestion->id,
    ]);

    $this->actingAs($user);

    Volt::test('tickets.index')
        ->set('statusFilter', (string) $statusClosed->id)
        ->set('typeFilter', (string) $typeIncident->id)
        ->set('channelFilter', (string) $channelChat->id)
        ->assertSee($matchingTicket->title)
        ->assertDontSee('Email Question Open');
});

test('ticket messages can be added', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();

    $this->actingAs($user);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->set('newMessage', 'Dit is een reactie')
        ->call('addMessage')
        ->assertHasNoErrors();

    expect($ticket->messages()->count())->toBe(1)
        ->and($ticket->messages()->first()->message)->toBe('Dit is een reactie');
});

test('ticket messages can be edited by author', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();
    $message = $ticket->messages()->create([
        'message' => 'Origineel bericht',
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->call('editMessage', $message->id)
        ->assertSet('editingMessageId', $message->id)
        ->assertSet('editingMessageContent', 'Origineel bericht')
        ->set('editingMessageContent', 'Aangepast bericht')
        ->call('updateMessage')
        ->assertHasNoErrors();

    expect($message->fresh()->message)->toBe('Aangepast bericht');
});

test('ticket messages can be deleted by author', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();
    $message = $ticket->messages()->create([
        'message' => 'Te verwijderen bericht',
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->call('deleteMessage', $message->id);

    expect($ticket->messages()->find($message->id))->toBeNull();
});

test('ticket messages cannot be edited by non-author', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = Ticket::factory()->for($author)->create();
    $message = $ticket->messages()->create([
        'message' => 'Origineel bericht',
        'user_id' => $author->id,
    ]);

    $this->actingAs($otherUser);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->call('editMessage', $message->id)
        ->assertSet('editingMessageId', null);

    expect($message->fresh()->message)->toBe('Origineel bericht');
});

test('ticket messages cannot be deleted by non-author', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = Ticket::factory()->for($author)->create();
    $message = $ticket->messages()->create([
        'message' => 'Te verwijderen bericht',
        'user_id' => $author->id,
    ]);

    $this->actingAs($otherUser);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->call('deleteMessage', $message->id);

    expect($ticket->messages()->find($message->id))->not->toBeNull();
});

test('ticket attachments can be uploaded', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();

    Storage::fake('public');

    $this->actingAs($user);

    $file = UploadedFile::fake()->image('test.jpg', 600, 400)->size(1024);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->set('attachments', [$file])
        ->call('uploadAttachments')
        ->assertHasNoErrors();

    expect($ticket->attachments()->count())->toBe(1)
        ->and($ticket->attachments()->first()->original_filename)->toBe('test.jpg')
        ->and($ticket->attachments()->first()->mime_type)->toBe('image/jpeg');

    Storage::disk('public')->assertExists($ticket->attachments()->first()->path);
});

test('ticket attachments can only be images or pdfs', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();

    Storage::fake('public');

    $this->actingAs($user);

    $file = UploadedFile::fake()->create('document.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->set('attachments', [$file])
        ->call('uploadAttachments')
        ->assertHasErrors(['attachments.0']);

    expect($ticket->attachments()->count())->toBe(0);
});

test('ticket attachments can be deleted by uploader', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();

    Storage::fake('public');

    $this->actingAs($user);

    $file = UploadedFile::fake()->image('test.jpg');
    $path = $file->store('ticket-attachments', 'public');

    $attachment = $ticket->attachments()->create([
        'user_id' => $user->id,
        'filename' => $file->hashName(),
        'original_filename' => $file->getClientOriginalName(),
        'mime_type' => $file->getMimeType(),
        'size' => $file->getSize(),
        'path' => $path,
    ]);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->call('deleteAttachment', $attachment->id);

    expect($ticket->attachments()->find($attachment->id))->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

test('ticket attachments cannot be deleted by non-uploader', function () {
    $uploader = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = Ticket::factory()->for($uploader)->create();

    Storage::fake('public');

    $file = UploadedFile::fake()->image('test.jpg');
    $path = $file->store('ticket-attachments', 'public');

    $attachment = $ticket->attachments()->create([
        'user_id' => $uploader->id,
        'filename' => $file->hashName(),
        'original_filename' => $file->getClientOriginalName(),
        'mime_type' => $file->getMimeType(),
        'size' => $file->getSize(),
        'path' => $path,
    ]);

    $this->actingAs($otherUser);

    Volt::test('tickets.form', ['ticket' => $ticket])
        ->call('deleteAttachment', $attachment->id);

    expect($ticket->attachments()->find($attachment->id))->not->toBeNull();
    Storage::disk('public')->assertExists($path);
});

test('ticket title is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('tickets.form')
        ->set('title', '')
        ->set('content', 'Test content')
        ->call('save')
        ->assertHasErrors(['title' => 'required']);
});

test('ticket content is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('tickets.form')
        ->set('title', 'Test Title')
        ->set('content', '')
        ->call('save')
        ->assertHasErrors(['content' => 'required']);
});
