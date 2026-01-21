<?php

use App\Models\{LeadItem, LeadStatus, LeadChannel, LeadCategory};
use Livewire\Volt\Component;

new class extends Component {
    public LeadItem $leadItem;
    public string $first_name = '';
    public string $last_name = '';
    public string $companyname = '';
    public string $streetname = '';
    public string $housenumber = '';
    public string $zipcode = '';
    public string $city = '';
    public string $emailadres = '';
    public string $phonenumber = '';
    public string $ipaddress = '';
    public string $internal_note = '';
    public ?int $lead_status_id = null;
    public ?int $lead_channel_id = null;
    public ?int $lead_category_id = null;

    public function mount(): void
    {
        $this->first_name = $this->leadItem->first_name ?? '';
        $this->last_name = $this->leadItem->last_name ?? '';
        $this->companyname = $this->leadItem->companyname ?? '';
        $this->streetname = $this->leadItem->streetname ?? '';
        $this->housenumber = $this->leadItem->housenumber ?? '';
        $this->zipcode = $this->leadItem->zipcode ?? '';
        $this->city = $this->leadItem->city ?? '';
        $this->emailadres = $this->leadItem->emailadres ?? '';
        $this->phonenumber = $this->leadItem->phonenumber ?? '';
        $this->ipaddress = $this->leadItem->ipaddress ?? '';
        $this->internal_note = $this->leadItem->internal_note ?? '';
        $this->lead_status_id = $this->leadItem->lead_status_id;
        $this->lead_channel_id = $this->leadItem->lead_channel_id;
        $this->lead_category_id = $this->leadItem->lead_category_id;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'companyname' => ['nullable', 'string', 'max:255'],
            'streetname' => ['nullable', 'string', 'max:255'],
            'housenumber' => ['nullable', 'string', 'max:255'],
            'zipcode' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'emailadres' => ['nullable', 'email', 'max:255'],
            'phonenumber' => ['nullable', 'string', 'max:255'],
            'ipaddress' => ['nullable', 'ip'],
            'internal_note' => ['nullable', 'string'],
            'lead_status_id' => ['nullable', 'exists:lead_statuses,id'],
            'lead_channel_id' => ['nullable', 'exists:lead_channels,id'],
            'lead_category_id' => ['nullable', 'exists:lead_categories,id'],
        ]);

        // Convert empty strings to null for foreign keys
        if (empty($validated['lead_status_id'])) {
            $validated['lead_status_id'] = null;
        }
        if (empty($validated['lead_channel_id'])) {
            $validated['lead_channel_id'] = null;
        }
        if (empty($validated['lead_category_id'])) {
            $validated['lead_category_id'] = null;
        }

        $this->leadItem->update($validated);

        $this->redirect(route('lead-items.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'leadStatuses' => LeadStatus::where('active', true)->orderBy('list_order')->get(),
            'leadChannels' => LeadChannel::where('active', true)->orderBy('list_order')->get(),
            'leadCategories' => LeadCategory::where('active', true)->orderBy('list_order')->get(),
        ];
    }
}; ?>

<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <flux:heading size="xl">{{ __('Edit Lead') }}</flux:heading>
        <flux:subheading>{{ __('Update lead details') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="grid grid-cols-2 gap-4">
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
            wire:model="companyname"
            :label="__('Company Name')"
            type="text"
            :placeholder="__('Acme Inc.')"
        />

        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <flux:input
                    wire:model="streetname"
                    :label="__('Street Name')"
                    type="text"
                    :placeholder="__('Main Street')"
                />
            </div>

            <flux:input
                wire:model="housenumber"
                :label="__('House Number')"
                type="text"
                :placeholder="__('123')"
            />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:input
                wire:model="zipcode"
                :label="__('Zip Code')"
                type="text"
                :placeholder="__('1234 AB')"
            />

            <flux:input
                wire:model="city"
                :label="__('City')"
                type="text"
                :placeholder="__('Amsterdam')"
            />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:input
                wire:model="emailadres"
                :label="__('Email Address')"
                type="email"
                :placeholder="__('john@example.com')"
            />

            <flux:input
                wire:model="phonenumber"
                :label="__('Phone Number')"
                type="text"
                :placeholder="__('+31 6 12345678')"
            />
        </div>

        <flux:input
            wire:model="ipaddress"
            :label="__('IP Address')"
            type="text"
            :placeholder="__('192.168.1.1')"
        />

        <div class="grid grid-cols-3 gap-4">
            <flux:select
                wire:model="lead_status_id"
                :label="__('Status')"
                :placeholder="__('Select status...')"
            >
            <option value="">{{ __('None') }}</option>
                @foreach($leadStatuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model="lead_channel_id"
                :label="__('Channel')"
                :placeholder="__('Select channel...')"
            >
            <option value="">{{ __('None') }}</option>
                @foreach($leadChannels as $channel)
                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model="lead_category_id"
                :label="__('Category')"
                :placeholder="__('Select category...')"
            >
            <option value="">{{ __('None') }}</option>
                @foreach($leadCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </flux:select>
        </div>

        <flux:textarea
            wire:model="internal_note"
            :label="__('Internal Note')"
            rows="3"
            :placeholder="__('Add any internal notes here...')"
        />

        <div class="flex items-center justify-between gap-4">
            <flux:button variant="ghost" :href="route('lead-items.index')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Update Lead') }}
            </flux:button>
        </div>
    </form>
</div>
