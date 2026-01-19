<?php

declare(strict_types=1);

use App\Models\Account;
use App\Models\User;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;

test('users index page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertOk();
});

test('users create page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('users.create'));

    $response->assertOk();
});

test('users can be created', function () {
    $authUser = User::factory()->create();
    $account = Account::factory()->create();
    $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);

    $this->actingAs($authUser);

    Volt::test('users.form')
        ->set('name', 'John Doe')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('telephone_number', '+31612345678')
        ->set('account_ids', [$account->id])
        ->set('role_id', (string) $role->id)
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('save')
        ->assertHasNoErrors();

    expect(User::where('email', 'john@example.com')->exists())->toBeTrue();
});

test('users can be updated', function () {
    $authUser = User::factory()->create();
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $this->actingAs($authUser);

    Volt::test('users.form', ['user' => $user])
        ->set('name', 'Updated Name')
        ->set('email', 'updated@example.com')
        ->call('save')
        ->assertHasNoErrors();

    expect($user->fresh()->name)->toBe('Updated Name')
        ->and($user->fresh()->email)->toBe('updated@example.com');
});

test('users can be deleted', function () {
    $authUser = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($authUser);

    Volt::test('users.index')
        ->call('delete', $user->id);

    expect(User::find($user->id))->toBeNull();
});

test('user name is required', function () {
    $authUser = User::factory()->create();

    $this->actingAs($authUser);

    Volt::test('users.form')
        ->set('name', '')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('user email is required', function () {
    $authUser = User::factory()->create();

    $this->actingAs($authUser);

    Volt::test('users.form')
        ->set('name', 'Test User')
        ->set('email', '')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('save')
        ->assertHasErrors(['email' => 'required']);
});

test('user email must be unique', function () {
    $authUser = User::factory()->create();
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    $this->actingAs($authUser);

    Volt::test('users.form')
        ->set('name', 'Test User')
        ->set('email', 'existing@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('save')
        ->assertHasErrors(['email']);
});

test('user password is required when creating', function () {
    $authUser = User::factory()->create();

    $this->actingAs($authUser);

    Volt::test('users.form')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', '')
        ->call('save')
        ->assertHasErrors(['password' => 'required']);
});

test('user password must be confirmed when creating', function () {
    $authUser = User::factory()->create();

    $this->actingAs($authUser);

    Volt::test('users.form')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'different')
        ->call('save')
        ->assertHasErrors(['password']);
});

test('user password is optional when updating', function () {
    $authUser = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($authUser);

    Volt::test('users.form', ['user' => $user])
        ->set('name', 'Updated Name')
        ->set('password', '')
        ->set('password_confirmation', '')
        ->call('save')
        ->assertHasNoErrors();
});

test('user password must be confirmed when updating if provided', function () {
    $authUser = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($authUser);

    Volt::test('users.form', ['user' => $user])
        ->set('name', 'Updated Name')
        ->set('password', 'newpassword123')
        ->set('password_confirmation', 'different')
        ->call('save')
        ->assertHasErrors(['password']);
});
