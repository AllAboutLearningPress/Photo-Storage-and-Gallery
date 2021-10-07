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

        // normal user
        $normal_user = Role::forceCreate([
            'id' => 2,
            'name' => 'Normal User',
            'slug' => 'normal-user'
        ]);

        $perm_ids_for_normal_user = Permission::where('slug', 'like', 'photos.%')->select('id')->pluck('id')->all();
        $normal_user->permissions()->sync($perm_ids_for_normal_user);
    }
}
