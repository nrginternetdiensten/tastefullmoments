<?php

use App\Models\BlogCategory;
use Livewire\Volt\Component;
use Illuminate\Support\Str;

new class extends Component {
    public BlogCategory $blogCategory;

    public string $name = '';
    public string $seo_title = '';
    public string $seo_keywords = '';
    public string $seo_description = '';
    public string $seo_url = '';
    public string $content = '';
    public bool $active = true;
    public int $list_order = 10;

    public function mount(BlogCategory $blogCategory): void
    {
        $this->blogCategory = $blogCategory;
        $this->name = $blogCategory->name;
        $this->seo_title = $blogCategory->seo_title ?? '';
        $this->seo_keywords = $blogCategory->seo_keywords ?? '';
        $this->seo_description = $blogCategory->seo_description ?? '';
        $this->seo_url = $blogCategory->seo_url;
        $this->content = $blogCategory->content ?? '';
        $this->active = $blogCategory->active;
        $this->list_order = $blogCategory->list_order;
    }

    public function updatedName(): void
    {
        // Auto-generate seo_title from name if seo_title is empty
        if (empty($this->seo_title)) {
            $this->seo_title = $this->name;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
            'seo_url' => ['required', 'string', 'max:255', 'unique:blog_categories,seo_url,' . $this->blogCategory->id],
            'content' => ['nullable', 'string'],
            'active' => ['boolean'],
            'list_order' => ['required', 'integer', 'min:0'],
        ]);

        $this->blogCategory->update($validated);

        $this->redirect(route('blog-categories.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Edit Blog Category') }}</flux:heading>
        <flux:subheading>{{ __('Update blog category details') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="space-y-6">
            <flux:input
                wire:model.blur="name"
                :label="__('Name')"
                type="text"
                required
                autofocus
                :placeholder="__('Category Name')"
            />

            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                <flux:heading size="lg" class="mb-4">{{ __('SEO Settings') }}</flux:heading>

                <div class="space-y-4">
                    <flux:input
                        wire:model="seo_title"
                        :label="__('SEO Title')"
                        type="text"
                        :placeholder="__('Will default to category name if empty')"
                    />

                    <flux:input
                        wire:model="seo_url"
                        :label="__('SEO URL')"
                        type="text"
                        required
                        :placeholder="__('category-url-slug')"
                    />

                    <flux:textarea
                        wire:model="seo_keywords"
                        :label="__('SEO Keywords')"
                        :placeholder="__('keyword1, keyword2, keyword3')"
                        rows="2"
                    />

                    <flux:textarea
                        wire:model="seo_description"
                        :label="__('SEO Description')"
                        :placeholder="__('Meta description for search engines')"
                        rows="3"
                    />
                </div>
            </div>

            <flux:textarea
                wire:model="content"
                :label="__('Content')"
                :placeholder="__('Category description or content')"
                rows="8"
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model="list_order"
                    :label="__('Display Order')"
                    type="number"
                    required
                    min="0"
                />

                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Status') }}</label>
                    <flux:checkbox wire:model="active" :label="__('Active')" />
                </div>
            </div>
        </div>

        <div class="flex justify-between">
            <flux:button :href="route('blog-categories.index')" wire:navigate variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary">
                {{ __('Update Category') }}
            </flux:button>
        </div>
    </form>
</div>
