<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $this->authorize('users.delete');
        
        $user = User::findOrFail($id);
        $user->delete();

        $this->dispatch('user-deleted');
    }

    public function with(): array
    {
        return [
            'users' => User::query()
                ->when($this->search, fn($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                )
                ->with(['roles', 'accounts'])
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Users') }}</flux:heading>
            <flux:subheading>{{ __('Manage user accounts and roles') }}</flux:subheading>
        </div>
        @can('users.create')
            <flux:button :href="route('users.create')" wire:navigate variant="primary" icon="plus">
                {{ __('New User') }}
            </flux:button>
        @endcan
    </div>

    <div class="flex items-center gap-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search users...') }}"
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('User') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Accounts') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Role') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($users as $user)
                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4">
                            <div>
                                <flux:text class="font-medium">{{ $user->name }}</flux:text>
                                <flux:text class="text-xs text-zinc-500">{{ $user->email }}</flux:text>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $accountNames = $user->accounts->pluck('name');
                                $displayNames = $accountNames->take(2)->join(', ');
                                $extraCount = max($accountNames->count() - 2, 0);
                            @endphp

                            @if($accountNames->isNotEmpty())
                                <flux:text class="text-sm">
                                    {{ $displayNames }}@if($extraCount) {{ ' +' . $extraCount }} {{ __('more') }} @endif
                                </flux:text>
                            @else
                                <flux:text class="text-sm text-zinc-400">{{ __('No accounts') }}</flux:text>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($user->roles->first())
                                <flux:badge variant="success">{{ $user->roles->first()->name }}</flux:badge>
                            @else
                                <flux:badge variant="warning">{{ __('No role') }}</flux:badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('users.edit')
                                    <flux:button size="sm" :href="route('users.edit', $user)" wire:navigate variant="ghost">
                                        {{ __('Edit') }}
                                    </flux:button>
                                    <flux:button size="sm" :href="route('users.edit-role', $user)" wire:navigate variant="ghost">
                                        {{ __('Edit Role') }}
                                    </flux:button>
                                @endcan
                                @can('users.delete')
                                    <flux:modal.trigger :name="'delete-user-'.$user->id">
                                        <flux:button size="sm" variant="danger">
                                            {{ __('Delete') }}
                                        </flux:button>
                                    </flux:modal.trigger>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <flux:text class="text-zinc-500">{{ __('No users found.') }}</flux:text>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>

    @foreach ($users as $user)
        <flux:modal :name="'delete-user-'.$user->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete User?') }}</flux:heading>
                    <flux:text class="mt-2">
                        <p>{{ __('Are you sure you want to delete') }} {{ $user->name }}?</p>
                        <p>{{ __('This action cannot be undone.') }}</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button variant="danger" wire:click="delete({{ $user->id }})">{{ __('Delete User') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
