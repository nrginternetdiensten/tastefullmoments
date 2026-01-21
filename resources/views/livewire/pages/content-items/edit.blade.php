<?php

use App\Models\ContentItem;
use App\Models\ContentFolder;
use App\Models\ContentType;
use Livewire\Volt\Component;

new class extends Component {
    public ContentItem $contentItem;

    public string $name = '';
    public string $seo_title = '';
    public string $seo_keywords = '';
    public string $seo_description = '';
    public string $seo_url = '';
    public ?int $folder_id = null;
    public ?int $type_id = null;
    public string $content = '';
    public string $currentTab = 'general';

    public function mount(ContentItem $contentItem): void
    {
        $this->contentItem = $contentItem;
        $this->name = $contentItem->name;
        $this->seo_title = $contentItem->seo_title ?? '';
        $this->seo_keywords = $contentItem->seo_keywords ?? '';
        $this->seo_description = $contentItem->seo_description ?? '';
        $this->seo_url = $contentItem->seo_url ?? '';
        $this->folder_id = $contentItem->folder_id;
        $this->type_id = $contentItem->type_id;
        $this->content = $contentItem->content ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'seo_url' => ['nullable', 'string', 'max:255'],
            'folder_id' => ['nullable', 'exists:content_folders,id'],
            'type_id' => ['nullable', 'exists:content_types,id'],
            'content' => ['nullable', 'string'],
        ]);

        $this->contentItem->update($validated);

        $this->redirect(route('content-items.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'folders' => ContentFolder::query()->where('active', true)->orderBy('list_order')->orderBy('name')->get(),
            'types' => ContentType::query()->where('active', true)->orderBy('list_order')->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Edit Content Item') }}</flux:heading>
        <flux:subheading>{{ __('Update content item') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <flux:card>
            <flux:tab.group>
                <flux:tabs wire:model="currentTab">
                    <flux:tab name="general">{{ __('General') }}</flux:tab>
                    <flux:tab name="content">{{ __('Content') }}</flux:tab>
                    <flux:tab name="seo">{{ __('SEO') }}</flux:tab>
                </flux:tabs>

                <flux:tab.panel name="general">
                    <div class="space-y-6">
                        <flux:field>
                            <flux:label>{{ __('Name') }}</flux:label>
                            <flux:input wire:model="name" placeholder="{{ __('Enter item name...') }}" />
                            <flux:error name="name" />
                        </flux:field>

                        <div class="grid gap-6 md:grid-cols-2">
                            <flux:field>
                                <flux:label>{{ __('Folder') }}</flux:label>
                                <flux:select wire:model="folder_id" placeholder="{{ __('Select folder...') }}">
                                    <option value="">{{ __('-- No Folder --') }}</option>
                                    @foreach($folders as $folder)
                                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="folder_id" />
                            </flux:field>

                            <flux:field>
                                <flux:label>{{ __('Type') }}</flux:label>
                                <flux:select wire:model="type_id" placeholder="{{ __('Select type...') }}">
                                    <option value="">{{ __('-- No Type --') }}</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="type_id" />
                            </flux:field>
                        </div>
                    </div>
                </flux:tab.panel>

                <flux:tab.panel name="content">
                    <div class="space-y-6">
                        <flux:field>
                            <flux:label>{{ __('Content') }}</flux:label>
                            <flux:textarea wire:model="content" rows="20" placeholder="{{ __('Enter content...') }}" />
                            <flux:error name="content" />
                        </flux:field>
                    </div>
                </flux:tab.panel>

                <flux:tab.panel name="seo">
                    <div class="space-y-6">
                        <flux:field>
                            <flux:label>{{ __('SEO Title') }}</flux:label>
                            <flux:input wire:model="seo_title" placeholder="{{ __('Enter SEO title...') }}" />
                            <flux:error name="seo_title" />
                        </flux:field>

                        <flux:field>
                            <flux:label>{{ __('SEO URL') }}</flux:label>
                            <flux:input wire:model="seo_url" placeholder="{{ __('Enter SEO-friendly URL slug...') }}" />
                            <flux:error name="seo_url" />
                        </flux:field>

                        <flux:field>
                            <flux:label>{{ __('SEO Keywords') }}</flux:label>
                            <flux:input wire:model="seo_keywords" placeholder="{{ __('Enter keywords (comma-separated)...') }}" />
                            <flux:error name="seo_keywords" />
                        </flux:field>

                        <flux:field>
                            <flux:label>{{ __('SEO Description') }}</flux:label>
                            <flux:textarea wire:model="seo_description" rows="4" placeholder="{{ __('Enter meta description...') }}" />
                            <flux:error name="seo_description" />
                        </flux:field>
                    </div>
                </flux:tab.panel>
            </flux:tab.group>
        </flux:card>

        <div class="flex items-center justify-between">
            <flux:button href="{{ route('content-items.index') }}" wire:navigate variant="ghost">
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Update Item') }}
            </flux:button>
        </div>
    </form>
</div>
