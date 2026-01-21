<?php

use App\Models\Order;
use App\Models\User;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('orders index can be rendered', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $permission = Permission::firstOrCreate(['name' => 'orders.index']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertOk();
});

test('orders are displayed', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $permission = Permission::firstOrCreate(['name' => 'orders.index']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);

    $order = Order::factory()->create();

    Volt::actingAs($user)->test('orders.index')
        ->assertSee($order->order_id);
});
