<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    public ?User $user = null;

    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $first_name = '';

    #[Modelable]
    public string $last_name = '';

    #[Modelable]
    public string $email = '';

    #[Modelable]
    public string $telephone_number = '';

    #[Modelable]
    public array $account_ids = [];

    #[Modelable]
    public ?string $role_id = null;

    #[Modelable]
    public string $password = '';

    #[Modelable]
    public string $password_confirmation = '';

    public function mount(): void
    {
        if ($this->user) {
            $this->name = $this->user->name;
            $this->first_name = $this->user->first_name ?? '';
            $this->last_name = $this->user->last_name ?? '';
            $this->email = $this->user->email;
            $this->telephone_number = $this->user->telephone_number ?? '';
            $this->account_ids = $this->user->accounts->pluck('id')->toArray();
            $this->role_id = $this->user->roles->first()?->id ? (string) $this->user->roles->first()->id : null;
        }
    }

    public function save(): void
    {
        $this->authorize($this->user ? 'users.edit' : 'users.create');
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . ($this->user?->id ?? 'NULL')],
            'telephone_number' => ['nullable', 'string', 'max:255'],
            'account_ids' => ['nullable', 'array'],
            'account_ids.*' => ['exists:accounts,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ];

        if (!$this->user) {
            // Creating new user - password and role are required
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['role_id'] = ['required', 'exists:roles,id'];
        } elseif ($this->password) {
            // Updating existing user - password is optional but must be confirmed if provided
            $rules['password'] = ['string', 'min:8', 'confirmed'];
        }

        $validated = $this->validate($rules);

        if ($this->user) {
            // Update existing user
            if (empty($validated['password'])) {
                unset($validated['password']);
            }
            $this->user->update($validated);
        } else {
            // Create new user
            $this->user = User::create($validated);
        }

        // Sync accounts
        if (isset($validated['account_ids'])) {
            $this->user->accounts()->sync($validated['account_ids']);
        }

        // Sync role
        if (isset($validated['role_id'])) {
            $role = \Spatie\Permission\Models\Role::find($validated['role_id']);
            if ($role) {
                $this->user->syncRoles([$role->name]);
            }
        }

        $this->dispatch('user-saved');
        $this->redirect(route('users.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'accounts' => \App\Models\Account::orderBy('name')->get(),
            'roles' => \Spatie\Permission\Models\Role::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <flux:heading size="xl">{{ $user ? __('Edit User') : __('Create User') }}</flux:heading>
        <flux:subheading>{{ $user ? __('Update user account details') : __('Add a new user to the system') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:input
            wire:model="name"
            :label="__('Display Name')"
            type="text"
            required
            autofocus
            :placeholder="__('John Doe')"
        />

        <div class="grid gap-6 md:grid-cols-2">
            <flux:input
                wire:model="first_name"
                :label="__('First Name')"
                type="text"
                :placeholder="__('John')"
            />

            <flux:input
                wire:model="last_name"
                :label="__('Last Name')"
                type="text"
                :placeholder="__('Doe')"
            />
        </div>

        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            required
            :placeholder="__('john@example.com')"
        />

        <flux:input
            wire:model="telephone_number"
            :label="__('Telephone Number')"
            type="tel"
            :placeholder="__('+31 6 12345678')"
        />

        <flux:select
            wire:model="account_ids"
            :label="__('Accounts')"
            variant="listbox"
            multiple
            searchable
            clearable
        >
            @foreach($accounts as $account)
                <flux:select.option value="{{ $account->id }}">{{ $account->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select
            wire:model="role_id"
            :label="__('Role')"
            variant="listbox"
            clearable
            searchable
        >
            @foreach($roles as $role)
                <flux:select.option value="{{ $role->id }}">{{ $role->name }}</flux:select.option>
            @endforeach
        </flux:select>

        @if(!$user)
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                :placeholder="__('Minimum 8 characters')"
            />

            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                :placeholder="__('Re-enter password')"
            />
        @else
            <flux:input
                wire:model="password"
                :label="__('New Password')"
                type="password"
                :placeholder="__('Leave blank to keep current password')"
            />

            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm New Password')"
                type="password"
                :placeholder="__('Re-enter new password')"
            />
        @endif

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('users.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $user ? __('Update User') : __('Create User') }}
            </flux:button>
        </div>
    </form>
</div>
