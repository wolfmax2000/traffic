<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$TGbF5HINiBA4P.CJ.JOvNO8yI30IG4BiF8j5SaXXTREyduih9TP7O',
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
