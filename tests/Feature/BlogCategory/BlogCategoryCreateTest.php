<?php

declare(strict_types=1);

use App\Models\BlogCategory;
use App\Models\User;
use Livewire\Volt\Volt;

test('authenticated user can view create blog category page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('blog-categories.create'))
        ->assertSuccessful();
});

test('unauthenticated user cannot view create blog category page', function () {
    $this->get(route('blog-categories.create'))
        ->assertRedirect(route('login'));
});

test('user can create a blog category', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->set('name', 'Laravel Tips')
        ->set('seo_title', 'Best Laravel Tips and Tricks')
        ->set('seo_keywords', 'laravel, php, tips, tricks')
        ->set('seo_description', 'Learn the best Laravel tips and tricks')
        ->set('seo_url', 'laravel-tips')
        ->set('content', 'This category contains Laravel tips and tricks.')
        ->set('list_order', 15)
        ->set('active', true)
        ->call('save')
        ->assertRedirect(route('blog-categories.index'));

    expect(BlogCategory::where('name', 'Laravel Tips')->exists())->toBeTrue();

    $category = BlogCategory::where('name', 'Laravel Tips')->first();
    expect($category->seo_title)->toBe('Best Laravel Tips and Tricks')
        ->and($category->seo_url)->toBe('laravel-tips')
        ->and($category->active)->toBeTrue()
        ->and($category->list_order)->toBe(15);
});

test('seo_url is auto-generated from name when empty', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->set('name', 'Vue.js Guides')
        ->assertSet('seo_url', 'vuejs-guides');
});

test('seo_title is auto-generated from name when empty', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->set('name', 'React Tutorials')
        ->assertSet('seo_title', 'React Tutorials');
});

test('name is required when creating blog category', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->set('name', '')
        ->set('seo_url', 'test-url')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('seo_url is required when creating blog category', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->set('name', 'Test Category')
        ->set('seo_url', '')
        ->call('save')
        ->assertHasErrors(['seo_url' => 'required']);
});

test('seo_url must be unique when creating blog category', function () {
    $user = User::factory()->create();
    BlogCategory::factory()->create(['seo_url' => 'existing-url']);

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->set('name', 'Test Category')
        ->set('seo_url', 'existing-url')
        ->call('save')
        ->assertHasErrors(['seo_url' => 'unique']);
});

test('list_order must be a positive integer', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->set('name', 'Test Category')
        ->set('seo_url', 'test-url')
        ->set('list_order', -1)
        ->call('save')
        ->assertHasErrors(['list_order' => 'min']);
});

test('blog category defaults to active', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->assertSet('active', true);
});

test('blog category defaults to list_order 10', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.create')
        ->assertSet('list_order', 10);
});
