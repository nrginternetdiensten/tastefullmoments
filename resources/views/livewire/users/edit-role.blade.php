<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?User $user = null;

    #[Modelable]
    public ?int $selectedRole = null;

    public function mount(): void
    {
        if ($this->user && $this->user->roles->first()) {
            $this->selectedRole = $this->user->roles->first()->id;
        }
    }

    public function save(): void
    {
        $this->validate([
            'selectedRole' => ['nullable', 'exists:roles,id'],
        ]);

        // Verwijder alle bestaande rollen
        $this->user->syncRoles([]);

        // Wijs nieuwe rol toe als er een is geselecteerd
        if ($this->selectedRole) {
            $role = Role::find($this->selectedRole);
            $this->user->assignRole($role);
        }

        $this->dispatch('role-assigned');
        $this->redirect(route('users.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'roles' => Role::all(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Edit User Role') }}</flux:heading>
        <flux:subheading>{{ __('Assign a role to :name', ['name' => $user->name]) }}</flux:subheading>
    </div>

    <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="mb-4">
            <flux:text class="font-medium">{{ $user->name }}</flux:text>
            <flux:text class="text-sm text-zinc-500">{{ $user->email }}</flux:text>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div>
                <label class="mb-3 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Select Role') }}</label>
                <flux:radio.group wire:model="selectedRole" variant="cards">
                    <flux:radio :value="null">{{ __('No Role') }}</flux:radio>
                    @foreach($roles as $role)
                        <flux:radio :value="$role->id">
                            <div>
                                <div class="font-medium">{{ $role->name }}</div>
                                <div class="text-xs text-zinc-500">{{ $role->permissions->count() }} {{ __('permissions') }}</div>
                            </div>
                        </flux:radio>
                    @endforeach
                </flux:radio.group>
                <flux:text class="mt-2 text-xs text-zinc-500">{{ __('Users can only have one role') }}</flux:text>
            </div>

            <div class="flex items-center justify-between gap-4">
                <flux:button variant="ghost" :href="route('users.index')" wire:navigate>
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="submit" variant="primary">
                    {{ __('Save Role') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
