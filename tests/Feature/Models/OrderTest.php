<?php

use App\Models\Account;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\OrderStatus;
use App\Models\User;

test('order can be created with all relationships', function () {
    $orderStatus = OrderStatus::factory()->create();
    $account = Account::factory()->create();
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'status_id' => $orderStatus->id,
        'account_id' => $account->id,
        'user_id' => $user->id,
    ]);

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->order_id)->toBeString()
        ->and($order->total)->toBeNumeric()
        ->and($order->total_tax)->toBeNumeric()
        ->and($order->total_exc)->toBeNumeric()
        ->and($order->status)->toBeInstanceOf(OrderStatus::class)
        ->and($order->account)->toBeInstanceOf(Account::class)
        ->and($order->user)->toBeInstanceOf(User::class);
});

test('order status has color scheme relationship', function () {
    $orderStatus = OrderStatus::factory()->create();

    expect($orderStatus)->toBeInstanceOf(OrderStatus::class)
        ->and($orderStatus->name)->toBeString()
        ->and($orderStatus->active)->toBeBool()
        ->and($orderStatus->list_order)->toBeInt();
});

test('order can have multiple order lines', function () {
    $order = Order::factory()->create();

    OrderLine::factory()->count(3)->create([
        'order_id' => $order->id,
    ]);

    expect($order->lines)->toHaveCount(3)
        ->and($order->lines->first())->toBeInstanceOf(OrderLine::class)
        ->and($order->lines->first()->name)->toBeString()
        ->and($order->lines->first()->quantity)->toBeInt()
        ->and($order->lines->first()->total)->toBeNumeric();
});
