<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'accounts',
            'color-schemes',
            'email-folders',
            'email-items',
            'invoices',
            'invoice-lines',
            'invoice-statuses',
            'invoice-taxes',
            'tickets',
            'ticket-attachments',
            'ticket-channels',
            'ticket-messages',
            'ticket-statuses',
            'ticket-types',
            'users',
        ];

        $actions = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        $this->command->info('Created permissions for all modules.');
    }
}
