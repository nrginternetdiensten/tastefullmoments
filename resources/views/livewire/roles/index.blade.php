<?php

use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(Role $role): void
    {
        $role->delete();

        $this->dispatch('role-deleted');
    }

    public function with(): array
    {
        return [
            'roles' => Role::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                )
                ->withCount('permissions')
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Roles') }}</flux:heading>
            <flux:subheading>{{ __('Manage user roles and their permissions') }}</flux:subheading>
        </div>

        <flux:button icon="plus" :href="route('roles.create')" wire:navigate variant="primary">
            {{ __('New Role') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search roles...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Name') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Permissions') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($roles as $role)
                    <tr wire:key="role-{{ $role->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:text class="font-medium">{{ $role->name }}</flux:text>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:badge>{{ $role->permissions_count }} {{ __('permissions') }}</flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" :href="route('roles.edit', $role)" wire:navigate variant="ghost">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    wire:click="delete({{ $role->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this role?') }}"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('No roles found.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $roles->links() }}
    </div>
</div>
