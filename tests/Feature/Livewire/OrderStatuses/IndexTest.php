<?php

use App\Models\OrderStatus;
use App\Models\User;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('order statuses index can be rendered', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $permission = Permission::firstOrCreate(['name' => 'order-statuses.index']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);

    $this->actingAs($user)
        ->get(route('order-statuses.index'))
        ->assertOk();
});

test('order statuses are displayed', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $permission = Permission::firstOrCreate(['name' => 'order-statuses.index']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);

    $orderStatus = OrderStatus::factory()->create(['name' => 'In behandeling']);

    Volt::actingAs($user)->test('order-statuses.index')
        ->assertSee('In behandeling');
});
