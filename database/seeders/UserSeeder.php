<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([

            'name' => 'Admin',
            'email' => 'gallery_admin@example.com',
            'password' => Hash::make('KzH5HJ#J^BEfJkeNBlkjndfgjkluihre'),
            'role_id' => 1

        ]);
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('photo@12345pass!'),
            'role_id' => 2
        ]);
    }
}
