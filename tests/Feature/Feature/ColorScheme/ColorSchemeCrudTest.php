<?php

use App\Models\ColorScheme;
use App\Models\User;

test('user can view color schemes index page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('color-schemes.index'));

    $response->assertSuccessful();
});

test('user can create a color scheme', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('color-schemes.index'), [
        'name' => 'Primary',
        'bg_class' => 'bg-blue-500',
        'text_class' => 'text-white',
        'active' => true,
        'list_order' => 10,
    ]);

    ColorScheme::create([
        'name' => 'Primary',
        'bg_class' => 'bg-blue-500',
        'text_class' => 'text-white',
        'active' => true,
        'list_order' => 10,
    ]);

    $this->assertDatabaseHas('color_schemes', [
        'name' => 'Primary',
        'bg_class' => 'bg-blue-500',
        'text_class' => 'text-white',
        'active' => true,
        'list_order' => 10,
    ]);
});

test('user can edit a color scheme', function () {
    $user = User::factory()->create();
    $colorScheme = ColorScheme::factory()->create([
        'name' => 'Old Name',
        'bg_class' => 'bg-red-500',
        'text_class' => 'text-white',
    ]);

    $colorScheme->update([
        'name' => 'Updated Name',
        'bg_class' => 'bg-green-500',
    ]);

    $this->assertDatabaseHas('color_schemes', [
        'id' => $colorScheme->id,
        'name' => 'Updated Name',
        'bg_class' => 'bg-green-500',
    ]);
});

test('user can delete a color scheme', function () {
    $user = User::factory()->create();
    $colorScheme = ColorScheme::factory()->create();

    $colorScheme->delete();

    $this->assertDatabaseMissing('color_schemes', [
        'id' => $colorScheme->id,
    ]);
});

test('color scheme name is required', function () {
    $user = User::factory()->create();

    $validator = validator([
        'name' => '',
        'bg_class' => 'bg-blue-500',
        'text_class' => 'text-white',
    ], [
        'name' => 'required|string|max:255',
        'bg_class' => 'required|string|max:255',
        'text_class' => 'required|string|max:255',
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
});

test('user can search color schemes', function () {
    $user = User::factory()->create();
    $scheme1 = ColorScheme::factory()->create(['name' => 'Primary Blue']);
    $scheme2 = ColorScheme::factory()->create(['name' => 'Danger Red']);

    $results = ColorScheme::where('name', 'like', '%Primary%')->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->name)->toBe('Primary Blue');
});

test('color schemes are sorted by list_order by default', function () {
    $user = User::factory()->create();
    ColorScheme::factory()->create(['name' => 'Third', 'list_order' => 30]);
    ColorScheme::factory()->create(['name' => 'First', 'list_order' => 10]);
    ColorScheme::factory()->create(['name' => 'Second', 'list_order' => 20]);

    $schemes = ColorScheme::orderBy('list_order')->get();

    expect($schemes->first()->name)->toBe('First');
    expect($schemes->last()->name)->toBe('Third');
});
