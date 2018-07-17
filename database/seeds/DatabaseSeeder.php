<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('statuses')->insert([
        	['name' => 'Новый'],
        	['name' => 'Ожидает оплаты'],
        	['name' => 'Оплачен']
        ]);
        DB::table('products')->insert([
        	['name' => 'Книга', 'price' => 100],
        	['name' => 'Стол', 'price' => 150],
        	['name' => 'Ручка', 'price' => 10],
        	['name' => 'Карандаш', 'price' => 15],
        	['name' => 'Линейка', 'price' => 25],
        	['name' => 'Тетрадь', 'price' => 30]
        ]);
    }
}
