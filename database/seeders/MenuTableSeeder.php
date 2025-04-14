<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Menu::create([
            'name' => 'яблоко',
            'price' => 10.99,
            'preparation_time' => 15,
        ]);

        Menu::create([
            'name' => 'абрикос',
            'price' => 20.50,
            'preparation_time' => 30,
        ]);

        Menu::create([
            'name' => 'сливы',
            'price' => 15.75,
            'preparation_time' => 20,
        ]);

        Menu::create([
            'name' => 'картошка',
            'price' => 200,
            'preparation_time' => 10,
        ]);

        Menu::create([
            'name' => 'оливье',
            'price' => 300,
            'preparation_time' => 25,
        ]);

        Menu::create([
            'name' => 'кофеек',
            'price' => 500,
            'preparation_time' => 5,
        ]);

        Menu::create([
            'name' => 'паста',
            'price' => 250,
            'preparation_time' => 40,
        ]);

        Menu::create([
            'name' => 'вода',
            'price' => 25.00,
            'preparation_time' => 35,
        ]);

        Menu::create([
            'name' => 'чай',
            'price' => 18.25,
            'preparation_time' => 50,
        ]);

        Menu::create([
            'name' => 'печеньки',
            'price' => 22.99,
            'preparation_time' => 45,
        ]);
    }
}
