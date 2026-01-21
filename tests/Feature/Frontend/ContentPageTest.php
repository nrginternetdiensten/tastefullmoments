<?php

declare(strict_types=1);

use App\Models\ContentFolder;
use App\Models\ContentItem;
use App\Models\ContentType;

uses()->group('frontend', 'content');

test('content page displays content item by seo url', function () {
    $folder = ContentFolder::factory()->create(['name' => 'Blog']);
    $type = ContentType::factory()->create(['name' => 'Artikel']);

    $content = ContentItem::factory()->create([
        'name' => 'Test Content Page',
        'seo_title' => 'Test SEO Title',
        'seo_description' => 'This is a test content description.',
        'seo_url' => 'test-content-page',
        'folder_id' => $folder->id,
        'type_id' => $type->id,
        'content' => '<h2>Test Content</h2><p>This is the main content.</p>',
    ]);

    $response = $this->get('/c-test-content-page.html');

    $response->assertOk()
        ->assertSee('Test Content Page')
        ->assertSee('This is a test content description.')
        ->assertSee('Test Content')
        ->assertSee('This is the main content.')
        ->assertSee($folder->name)
        ->assertSee($type->name);
});

test('content page can be accessed via route', function () {
    $content = ContentItem::factory()->create([
        'name' => 'Accessible Content',
        'seo_url' => 'accessible-content',
        'content' => '<p>Page content here</p>',
    ]);

    $response = $this->get('/c-accessible-content.html');

    $response->assertOk()
        ->assertSee('Accessible Content')
        ->assertSee('Page content here');
});

test('content page returns 404 for non-existent seo url', function () {
    $response = $this->get('/c-non-existent-page.html');

    $response->assertNotFound();
});

test('content page displays without folder and type', function () {
    $content = ContentItem::factory()->create([
        'name' => 'Simple Content',
        'seo_url' => 'simple-content',
        'folder_id' => null,
        'type_id' => null,
        'content' => '<p>Simple content without folder or type</p>',
    ]);

    $response = $this->get('/c-simple-content.html');

    $response->assertOk()
        ->assertSee('Simple Content')
        ->assertSee('Simple content without folder or type');
});

test('content page uses seo title when available', function () {
    $content = ContentItem::factory()->create([
        'name' => 'Internal Name',
        'seo_title' => 'Public SEO Title',
        'seo_url' => 'seo-title-test',
    ]);

    $response = $this->get('/c-seo-title-test.html');

    $response->assertOk()
        ->assertSeeInOrder(['<title>', 'Public SEO Title', '</title>'], false);
});

test('content page falls back to name when seo title is not set', function () {
    $content = ContentItem::factory()->create([
        'name' => 'Content Name Only',
        'seo_title' => null,
        'seo_url' => 'name-only-test',
    ]);

    $response = $this->get('/c-name-only-test.html');

    $response->assertOk()
        ->assertSeeInOrder(['<title>', 'Content Name Only', '</title>'], false);
});
