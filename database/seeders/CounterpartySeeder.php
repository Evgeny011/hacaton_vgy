<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Counterparty;
use Illuminate\Support\Facades\DB;

class CounterpartySeeder extends Seeder
{
    public function run()
    {
        Counterparty::truncate();

        $counterparties = [
            [
                'name' => 'ООО "Ромашка"',
                'inn' => '1234567890',
                'type' => 'company',
                'contact_person' => 'Иванов Петр Сергеевич',
                'phone' => '+7 (495) 123-45-67',
                'email' => 'info@romashka.ru',
                'address' => 'г. Москва, ул. Ленина, д. 1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'АО "Луч"',
                'inn' => '0987654321',
                'type' => 'company',
                'contact_person' => 'Сидорова Мария Ивановна',
                'phone' => '+7 (495) 765-43-21',
                'email' => 'contact@luch.ru',
                'address' => 'г. Москва, пр. Мира, д. 15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Иванов Иван Иванович',
                'inn' => '112233445566',
                'type' => 'individual',
                'contact_person' => null,
                'phone' => '+7 (916) 123-45-67',
                'email' => 'ivanov@mail.ru',
                'address' => 'г. Москва, ул. Пушкина, д. 10',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ПАО "СтройИнвест"',
                'inn' => '5566778899',
                'type' => 'company',
                'contact_person' => 'Петров Алексей Владимирович',
                'phone' => '+7 (495) 555-44-33',
                'email' => 'office@stroinvest.ru',
                'address' => 'г. Москва, ул. Строителей, д. 25',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Смирнова Анна Петровна',
                'inn' => '998877665544',
                'type' => 'individual',
                'contact_person' => null,
                'phone' => '+7 (925) 333-22-11',
                'email' => 'smirnova@gmail.com',
                'address' => 'г. Москва, ул. Гагарина, д. 5',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('counterparties')->insert($counterparties);
        
        $this->command->info('Контрагенты успешно созданы!');
    }
}