<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roles = [
            ['id' => 1, 'name' => 'Admin', 'slug' => 'admin'],
            ['id' => 2, 'name' => 'User', 'slug' => 'user']
        ];
        // foreach ($roles as $role) {
        //     Role::FirstOrCreate($role);
        // }
        $adminRole = Role::create([
            'id' => 1,
            'name' => 'Admin',
            'slug' => 'admin',
        ]);
        $allPermIds = Permission::select('id')->pluck('id')->all();

        $adminRole->permissions()->sync($allPermIds);
    }
}
