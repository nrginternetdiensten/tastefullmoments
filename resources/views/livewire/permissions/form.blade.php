<?php

use Spatie\Permission\Models\Permission;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?Permission $permission = null;

    #[Modelable]
    public string $name = '';
    
    public string $moduleName = '';
    public bool $index = false;
    public bool $create = false;
    public bool $edit = false;
    public bool $read = false;
    public bool $delete = false;

    public function mount(): void
    {
        if ($this->permission) {
            $this->name = $this->permission->name;
        }
    }

    public function save(): void
    {
        // Editing existing permission
        if ($this->permission) {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $this->permission->id],
            ]);
            
            $this->permission->update($validated);
            $this->dispatch('permission-updated');
            $this->redirect(route('permissions.index'), navigate: true);
            return;
        }
        
        // Creating new permission(s)
        $anyChecked = $this->index || $this->create || $this->edit || $this->read || $this->delete;
        
        if ($anyChecked && $this->moduleName) {
            // Create multiple permissions based on checkboxes
            $this->validate([
                'moduleName' => ['required', 'string', 'max:255'],
            ]);
            
            $permissions = [];
            if ($this->index) $permissions[] = $this->moduleName . '.index';
            if ($this->create) $permissions[] = $this->moduleName . '.create';
            if ($this->edit) $permissions[] = $this->moduleName . '.edit';
            if ($this->read) $permissions[] = $this->moduleName . '.read';
            if ($this->delete) $permissions[] = $this->moduleName . '.delete';
            
            foreach ($permissions as $permissionName) {
                Permission::create(['name' => $permissionName]);
            }
            
            $this->dispatch('permission-created');
        } else {
            // Create single custom permission
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            ]);
            
            Permission::create($validated);
            $this->dispatch('permission-created');
        }

        $this->redirect(route('permissions.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $permission ? __('Edit Permission') : __('Create Permission') }}</flux:heading>
        <flux:subheading>{{ $permission ? __('Update permission name') : __('Add a new permission') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        @if($permission)
            {{-- Editing existing permission --}}
            <flux:input
                wire:model="name"
                :label="__('Permission Name')"
                type="text"
                required
                autofocus
                :placeholder="__('edit articles')"
            />
        @else
            {{-- Creating new permission(s) --}}
            <div class="space-y-6">
                <div class="space-y-4">
                    <flux:heading size="lg">{{ __('Module Permissions') }}</flux:heading>
                    <flux:subheading>{{ __('Maak meerdere permissions aan voor een module') }}</flux:subheading>
                    
                    <flux:input
                        wire:model.live="moduleName"
                        :label="__('Module Naam')"
                        type="text"
                        :placeholder="__('bijv. articles, users, invoices')"
                    />
                    
                    @if($moduleName)
                        <div class="space-y-2">
                            <flux:subheading>{{ __('Selecteer acties:') }}</flux:subheading>
                            <div class="grid gap-3">
                                <flux:checkbox wire:model="index" :label="$moduleName . '.index'" />
                                <flux:checkbox wire:model="create" :label="$moduleName . '.create'" />
                                <flux:checkbox wire:model="edit" :label="$moduleName . '.edit'" />
                                <flux:checkbox wire:model="read" :label="$moduleName . '.read'" />
                                <flux:checkbox wire:model="delete" :label="$moduleName . '.delete'" />
                            </div>
                        </div>
                    @endif
                </div>
                
                <flux:separator text="OF" />
                
                <div class="space-y-4">
                    <flux:heading size="lg">{{ __('Custom Permission') }}</flux:heading>
                    <flux:subheading>{{ __('Maak een enkele custom permission aan') }}</flux:subheading>
                    
                    <flux:input
                        wire:model="name"
                        :label="__('Permission Name')"
                        type="text"
                        :placeholder="__('edit articles')"
                    />
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('permissions.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $permission ? __('Update Permission') : __('Create Permission') }}
            </flux:button>
        </div>
    </form>
</div>
