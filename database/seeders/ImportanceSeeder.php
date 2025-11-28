<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Importance;
use Illuminate\Support\Facades\DB;

class ImportanceSeeder extends Seeder
{
    public function run()
    {
        // Очищаем таблицу перед заполнением
        Importance::truncate();

        $importanceLevels = [
            [
                'name' => 'Обычная',
                'level' => 1,
                'color' => '#10b981',
                'icon' => 'flag',
                'description' => 'Обычный документ, не требующий срочного внимания',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Важная',
                'level' => 2,
                'color' => '#f59e0b',
                'icon' => 'exclamation-triangle',
                'description' => 'Важный документ, требует внимания в ближайшее время',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Срочная',
                'level' => 3,
                'color' => '#ef4444',
                'icon' => 'exclamation-circle',
                'description' => 'Срочный документ, требует немедленного внимания',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        // Вставляем данные в таблицу
        DB::table('importances')->insert($importanceLevels);
        
        $this->command->info('Уровни важности успешно созданы!');
    }
}