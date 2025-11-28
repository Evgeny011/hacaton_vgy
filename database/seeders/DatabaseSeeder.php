<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Сначала создаем основные справочники
        $this->call([
            DocumentTypeSeeder::class,
            ImportanceSeeder::class,
            CounterpartySeeder::class,
        ]);

        // Затем пользователей (если нужно)
        // $this->call(UserSeeder::class);
        
        // Затем документы (если нужно тестовые данные)
        // $this->call(DocumentSeeder::class);
    }
}