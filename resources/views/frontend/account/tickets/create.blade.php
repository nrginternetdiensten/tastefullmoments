<x-layouts.frontend title="Nieuw Ticket">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-zinc-900 dark:text-zinc-100">
            Mijn Account
        </h1>

        <div class="grid gap-8 lg:grid-cols-4">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <x-account-sidebar />
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <livewire:frontend.tickets.create />
            </div>
        </div>
    </div>
</x-layouts.frontend>
