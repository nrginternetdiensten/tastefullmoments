<?php

use App\Models\SettingsItem;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    /**
     * Get a setting value by ID.
     */
    function setting(int $id, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$id}", now()->addDay(), function () use ($id, $default) {
            $settingsItem = SettingsItem::find($id);

            return $settingsItem?->value ?? $default;
        });
    }
}
