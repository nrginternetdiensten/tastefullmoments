<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?Role $role = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public array $selectedPermissions = [];

    public function mount(): void
    {
        if ($this->role) {
            $this->name = $this->role->name;
            $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
        }
    }

    public function toggleAll(): void
    {
        $allPermissions = Permission::all()->pluck('name')->toArray();

        if (count($this->selectedPermissions) === count($allPermissions)) {
            $this->selectedPermissions = [];
        } else {
            $this->selectedPermissions = $allPermissions;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . ($this->role?->id ?? 'NULL')],
            'selectedPermissions' => ['array'],
        ]);

        if ($this->role) {
            $this->role->update(['name' => $validated['name']]);
            $this->role->syncPermissions($validated['selectedPermissions']);
            $this->dispatch('role-updated');
        } else {
            $role = Role::create(['name' => $validated['name']]);
            $role->syncPermissions($validated['selectedPermissions']);
            $this->dispatch('role-created');
        }

        $this->redirect(route('roles.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'permissions' => Permission::all(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $role ? __('Edit Role') : __('Create Role') }}</flux:heading>
        <flux:subheading>{{ $role ? __('Update role details and permissions') : __('Add a new role with permissions') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Role Name')"
            type="text"
            required
            autofocus
            :placeholder="__('Administrator')"
        />

        <div>
            <div class="mb-3 flex items-center justify-between">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Permissions') }}</label>
                <flux:button size="sm" variant="ghost" wire:click="toggleAll" type="button">
                    {{ count($selectedPermissions) === count($permissions) ? __('Deselect All') : __('Select All') }}
                </flux:button>
            </div>
            <div class="space-y-2 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                @forelse($permissions as $permission)
                    <flux:checkbox
                        wire:model="selectedPermissions"
                        :value="$permission->name"
                        :label="$permission->name"
                    />
                @empty
                    <flux:text class="text-zinc-500">{{ __('No permissions available.') }}</flux:text>
                @endforelse
            </div>
            <flux:text class="mt-1 text-xs text-zinc-500">{{ __('Select permissions for this role') }}</flux:text>
        </div>

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('roles.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $role ? __('Update Role') : __('Create Role') }}
            </flux:button>
        </div>
    </form>
</div>
