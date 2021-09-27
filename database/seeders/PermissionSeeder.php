<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'send-invite',
            'upload',
            'delete',
            'clear-trash',
            'add-tags',
            'can-download',
            'can-view-roles',
        ];
    }
}
