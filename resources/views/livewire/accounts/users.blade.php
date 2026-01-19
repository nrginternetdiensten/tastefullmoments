<?php

use App\Models\Account;
use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public Account $account;
    public ?int $userToRemove = null;
    public ?int $userToLink = null;

    public function confirmRemove(int $userId): void
    {
        $this->userToRemove = $userId;
        $this->modal('remove-user')->show();
    }

    public function removeUser(): void
    {
        if ($this->userToRemove) {
            $user = User::find($this->userToRemove);
            if ($user) {
                $user->accounts()->detach($this->account->id);
            }
            $this->userToRemove = null;
            $this->modal('remove-user')->close();
            $this->dispatch('account-user-removed');
        }
    }

    public function openLinkModal(): void
    {
        $this->userToLink = null;
        $this->modal('link-user')->show();
    }

    public function linkUser(): void
    {
        $this->validate([
            'userToLink' => ['required', 'exists:users,id'],
        ]);

        $user = User::find($this->userToLink);
        $user?->accounts()->syncWithoutDetaching([$this->account->id]);
        $this->userToLink = null;
        $this->modal('link-user')->close();
        $this->dispatch('account-user-linked');
    }

    public function with(): array
    {
        return [
            'users' => $this->account->users()->with('roles')->get(),
            'availableUsers' => User::whereDoesntHave('accounts', fn($query) => $query->where('accounts.id', $this->account->id))
                ->orderBy('name')
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="lg">{{ __('Account Users') }}</flux:heading>
            <flux:subheading>{{ __('Users linked to this account') }}</flux:subheading>
        </div>
        <flux:button wire:click="openLinkModal" variant="primary" icon="plus">
            {{ __('Link User') }}
        </flux:button>
    </div>

    @if($users->count() > 0)
        <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('User') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Email') }}
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
                    @foreach($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <td class="px-6 py-4">
                                <flux:text class="font-medium">{{ $user->name }}</flux:text>
                            </td>
                            <td class="px-6 py-4">
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ $user->email }}</flux:text>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($user->roles->first())
                                    <flux:badge variant="success">{{ $user->roles->first()->name }}</flux:badge>
                                @else
                                    <flux:badge variant="warning">{{ __('No role') }}</flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <flux:button
                                    size="sm"
                                    wire:click="confirmRemove({{ $user->id }})"
                                    variant="danger"
                                >
                                    {{ __('Remove') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded-lg border border-zinc-200 bg-white p-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
            <flux:text class="text-zinc-500">{{ __('No users linked to this account.') }}</flux:text>
        </div>
    @endif

    <flux:modal name="remove-user" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Remove User from Account?') }}</flux:heading>
                <flux:text class="mt-2">
                    <p>{{ __('You are about to remove this user from the account.') }}</p>
                    <p>{{ __('The user will no longer have access to this account.') }}</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button wire:click="removeUser" variant="danger">{{ __('Remove User') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="link-user" class="min-w-[28rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Link User to Account') }}</flux:heading>
                <flux:text class="mt-2">
                    {{ __('Select a user to link to this account.') }}
                </flux:text>
            </div>

            @if($availableUsers->count() > 0)
                <flux:select
                    wire:model="userToLink"
                    :label="__('User')"
                    variant="listbox"
                    searchable
                    :placeholder="__('Choose user...')"
                >
                    @foreach($availableUsers as $availableUser)
                        <flux:select.option value="{{ $availableUser->id }}">
                            {{ $availableUser->name }} ({{ $availableUser->email }})
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="linkUser" variant="primary">{{ __('Link User') }}</flux:button>
                </div>
            @else
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-6 text-center dark:border-zinc-700 dark:bg-zinc-800">
                    <flux:text class="text-zinc-500">{{ __('All users are already linked to an account.') }}</flux:text>
                </div>
                <div class="flex justify-end">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Close') }}</flux:button>
                    </flux:modal.close>
                </div>
            @endif
        </div>
    </flux:modal>
</div>
