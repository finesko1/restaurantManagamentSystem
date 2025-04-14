<?php

namespace Database\Seeders;

use App\Models\Cook;
use App\Models\Waiter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Cook::create([
            'name' => 'cook',
            'email' => 'cook@mail.ru',
            'password' => bcrypt('password'),
        ]);

        Waiter::create([
            'name' => 'waiter',
            'email' => 'waiter@mail.ru',
            'password' => bcrypt('password'),
        ]);
    }
}
