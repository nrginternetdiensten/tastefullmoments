<x-layouts.app :title="__('Edit Account')">
    <div class="mx-auto max-w-4xl space-y-8">
        <livewire:accounts.form :account="$account" />

        <livewire:accounts.users :account="$account" />

        <livewire:accounts.transactions :account="$account" />
    </div>
</x-layouts.app>
