<?php

use App\Models\LeadItem;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Validate('required|string|min:2|max:255')]
    public string $first_name = '';

    #[Validate('nullable|string|min:2|max:255')]
    public string $last_name = '';

    #[Validate('required|email|max:255')]
    public string $emailadres = '';

    #[Validate('nullable|string|max:255')]
    public string $phonenumber = '';

    #[Validate('nullable|string|max:255')]
    public string $companyname = '';

    #[Validate('required|string|min:3|max:255')]
    public string $subject = '';

    #[Validate('required|string|min:10|max:5000')]
    public string $message = '';

    public function submit(): void
    {
        $validated = $this->validate();

        // Create lead with channel_id = 1
        LeadItem::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'emailadres' => $validated['emailadres'],
            'phonenumber' => $validated['phonenumber'],
            'companyname' => $validated['companyname'],
            'internal_note' => "Subject: {$validated['subject']}\n\n{$validated['message']}",
            'ipaddress' => request()->ip(),
            'lead_channel_id' => 1,
            'lead_status_id' => \App\Models\LeadStatus::where('active', true)->orderBy('list_order')->first()?->id,
        ]);

        // Reset form
        $this->reset();

        // Show success message
        session()->flash('success', 'Bedankt voor uw bericht! We nemen zo snel mogelijk contact met u op.');
    }
}; ?>

<div>
    @if (session('success'))
        <flux:callout variant="success" class="mb-6">
            {{ session('success') }}
        </flux:callout>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <div class="grid gap-6 sm:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('Voornaam') }} *</flux:label>
                <flux:input wire:model="first_name" placeholder="{{ __('Uw voornaam') }}" />
                <flux:error name="first_name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Achternaam') }}</flux:label>
                <flux:input wire:model="last_name" placeholder="{{ __('Uw achternaam') }}" />
                <flux:error name="last_name" />
            </flux:field>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <flux:field>
                <flux:label>{{ __('E-mailadres') }} *</flux:label>
                <flux:input type="email" wire:model="emailadres" placeholder="uw@email.nl" />
                <flux:error name="emailadres" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Telefoonnummer') }}</flux:label>
                <flux:input type="tel" wire:model="phonenumber" placeholder="06-12345678" />
                <flux:error name="phonenumber" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>{{ __('Bedrijfsnaam') }}</flux:label>
            <flux:input wire:model="companyname" placeholder="{{ __('Optioneel') }}" />
            <flux:error name="companyname" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Onderwerp') }} *</flux:label>
            <flux:input wire:model="subject" placeholder="{{ __('Waar kunnen we u mee helpen?') }}" />
            <flux:error name="subject" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Bericht') }} *</flux:label>
            <flux:textarea wire:model="message" rows="6" placeholder="{{ __('Uw bericht...') }}" />
            <flux:error name="message" />
        </flux:field>

        <flux:button type="submit" variant="primary" class="w-full">
            <span wire:loading.remove>{{ __('Verstuur bericht') }}</span>
            <span wire:loading>{{ __('Versturen...') }}</span>
        </flux:button>
    </form>
</div>
