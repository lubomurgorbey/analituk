<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrNew([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
        if (!$user->exists) {
            $user->fill([
                'password' => bcrypt('ZJz4P5gk'),
                'remember_token' => str_random(60),
            ]);
            $user->save();
        }
    }
}
