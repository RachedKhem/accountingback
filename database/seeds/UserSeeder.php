<?php

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
        \App\User::create([
            'name' => 'khlil',
            'email' => 'khlilturki97@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => \App\Role::where('name', 'admin')->first()->id
        ]);
    }
}
