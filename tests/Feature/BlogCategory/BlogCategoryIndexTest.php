<?php

declare(strict_types=1);

use App\Models\BlogCategory;
use App\Models\User;
use Livewire\Volt\Volt;

test('authenticated user can view blog categories index page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('blog-categories.index'))
        ->assertSuccessful();
});

test('unauthenticated user cannot view blog categories index page', function () {
    $this->get(route('blog-categories.index'))
        ->assertRedirect(route('login'));
});

test('blog categories index displays paginated categories', function () {
    $user = User::factory()->create();
    BlogCategory::factory()->count(15)->create();

    Volt::test('pages.blog-categories.index')
        ->assertSee('Blog Categories')
        ->assertSuccessful();
});

test('user can search blog categories by name', function () {
    $user = User::factory()->create();
    BlogCategory::factory()->create(['name' => 'Laravel Tips']);
    BlogCategory::factory()->create(['name' => 'Vue.js Guides']);

    Volt::actingAs($user)
        ->test('pages.blog-categories.index')
        ->set('search', 'Laravel')
        ->assertSee('Laravel Tips')
        ->assertDontSee('Vue.js Guides');
});

test('user can search blog categories by seo url', function () {
    $user = User::factory()->create();
    BlogCategory::factory()->create(['name' => 'Tech', 'seo_url' => 'technology-news']);
    BlogCategory::factory()->create(['name' => 'Food', 'seo_url' => 'food-recipes']);

    Volt::actingAs($user)
        ->test('pages.blog-categories.index')
        ->set('search', 'technology')
        ->assertSee('Tech')
        ->assertDontSee('Food');
});

test('user can delete a blog category', function () {
    $user = User::factory()->create();
    $category = BlogCategory::factory()->create();

    Volt::actingAs($user)
        ->test('pages.blog-categories.index')
        ->call('delete', $category->id);

    expect(BlogCategory::find($category->id))->toBeNull();
});

test('blog categories are ordered by list_order', function () {
    $user = User::factory()->create();
    $category1 = BlogCategory::factory()->create(['list_order' => 20, 'name' => 'Second']);
    $category2 = BlogCategory::factory()->create(['list_order' => 10, 'name' => 'First']);
    $category3 = BlogCategory::factory()->create(['list_order' => 30, 'name' => 'Third']);

    Volt::actingAs($user)
        ->test('pages.blog-categories.index')
        ->assertSeeInOrder(['First', 'Second', 'Third']);
});
