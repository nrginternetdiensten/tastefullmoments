<?php

declare(strict_types=1);

use App\Models\BlogCategory;
use App\Models\User;
use Livewire\Volt\Volt;

test('authenticated user can view edit blog category page', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create();

    $this->actingAs($user)
        ->get(route('blog-categories.edit', $category))
        ->assertSuccessful();
});

test('unauthenticated user cannot view edit blog category page', function () {
    $category = BlogCategory::factory()->create();

    $this->get(route('blog-categories.edit', $category))
        ->assertRedirect(route('login'));
});

test('user can update a blog category', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create([
        'name' => 'Old Name',
        'seo_url' => 'old-url',
    ]);

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category])
        ->set('name', 'Updated Name')
        ->set('seo_title', 'Updated SEO Title')
        ->set('seo_url', 'updated-url')
        ->set('seo_keywords', 'updated, keywords')
        ->set('seo_description', 'Updated description')
        ->set('content', 'Updated content')
        ->set('list_order', 25)
        ->set('active', false)
        ->call('save')
        ->assertRedirect(route('blog-categories.index'));

    $category->refresh();

    expect($category->name)->toBe('Updated Name')
        ->and($category->seo_title)->toBe('Updated SEO Title')
        ->and($category->seo_url)->toBe('updated-url')
        ->and($category->list_order)->toBe(25)
        ->and($category->active)->toBeFalse();
});

test('edit page loads with existing blog category data', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create([
        'name' => 'Test Category',
        'seo_title' => 'SEO Title',
        'seo_keywords' => 'keyword1, keyword2',
        'seo_description' => 'SEO Description',
        'seo_url' => 'test-url',
        'content' => 'Test content',
        'list_order' => 20,
        'active' => false,
    ]);

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category])
        ->assertSet('name', 'Test Category')
        ->assertSet('seo_title', 'SEO Title')
        ->assertSet('seo_keywords', 'keyword1, keyword2')
        ->assertSet('seo_description', 'SEO Description')
        ->assertSet('seo_url', 'test-url')
        ->assertSet('content', 'Test content')
        ->assertSet('list_order', 20)
        ->assertSet('active', false);
});

test('seo_title is auto-updated from name when name changes and seo_title is empty', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create([
        'name' => 'Original Name',
        'seo_title' => null,
    ]);

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category])
        ->set('name', 'New Name')
        ->assertSet('seo_title', 'New Name');
});

test('name is required when updating blog category', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category])
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('seo_url is required when updating blog category', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category])
        ->set('seo_url', '')
        ->call('save')
        ->assertHasErrors(['seo_url' => 'required']);
});

test('seo_url must be unique when updating blog category except for current category', function () {
    $user = User::factory()->create();
    $category1 = BlogCategory::factory()->create(['seo_url' => 'existing-url']);
    $category2 = BlogCategory::factory()->create(['seo_url' => 'other-url']);

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category2])
        ->set('seo_url', 'existing-url')
        ->call('save')
        ->assertHasErrors(['seo_url' => 'unique']);
});

test('seo_url can remain the same when updating blog category', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create(['seo_url' => 'my-url']);

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category])
        ->set('name', 'Updated Name')
        ->set('seo_url', 'my-url')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('blog-categories.index'));

    $category->refresh();
    expect($category->seo_url)->toBe('my-url');
});

test('list_order must be a positive integer when updating', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.edit', ['blogCategory' => $category])
        ->set('list_order', -5)
        ->call('save')
        ->assertHasErrors(['list_order' => 'min']);
});
