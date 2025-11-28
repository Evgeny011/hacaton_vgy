<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('document_types')->insert([
            // Основные документы
            [
                'name' => 'Договор',
                'description' => 'Юридические договоры и соглашения',
                'extension' => 'pdf,doc,docx',
                'mime_type' => 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max_size' => 10485760,
                'is_active' => true,
                'category' => 'main', 
                'icon' => 'file-contract',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Счет',
                'description' => 'Финансовые документы и счета на оплату',
                'extension' => 'pdf,doc,docx,xls,xlsx',
                'mime_type' => 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'max_size' => 5242880,
                'is_active' => true,
                'category' => 'main', 
                'icon' => 'file-invoice-dollar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Акт',
                'description' => 'Акты выполненных работ и оказанных услуг',
                'extension' => 'pdf,doc,docx',
                'mime_type' => 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max_size' => 5242880,
                'is_active' => true,
                'category' => 'main', 
                'icon' => 'file-signature',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Приказ',
                'description' => 'Внутренние приказы и распоряжения',
                'extension' => 'pdf,doc,docx',
                'mime_type' => 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max_size' => 5242880,
                'is_active' => true,
                'category' => 'main', 
                'icon' => 'file-alt',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Дополнительные документы
            [
                'name' => 'Отчет',
                'description' => 'Финансовые и аналитические отчеты',
                'extension' => 'pdf,xls,xlsx,doc,docx',
                'mime_type' => 'application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max_size' => 10485760,
                'is_active' => true,
                'category' => 'additional',
                'icon' => 'chart-line',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Презентация',
                'description' => 'Презентации и бизнес-предложения',
                'extension' => 'pdf,ppt,pptx',
                'mime_type' => 'application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'max_size' => 20971520,
                'is_active' => true,
                'category' => 'additional',
                'icon' => 'presentation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Изображение',
                'description' => 'Фотографии, скриншоты и графические материалы',
                'extension' => 'jpg,jpeg,png,gif,bmp',
                'mime_type' => 'image/jpeg,image/png,image/gif,image/bmp',
                'max_size' => 5242880,
                'is_active' => true,
                'category' => 'additional', 
                'icon' => 'file-image',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Архив',
                'description' => 'Архивные файлы и сжатые папки',
                'extension' => 'zip,rar,7z,tar,gz',
                'mime_type' => 'application/zip,application/x-rar-compressed,application/x-7z-compressed,application/x-tar,application/gzip',
                'max_size' => 52428800,
                'is_active' => true,
                'category' => 'additional', 
                'icon' => 'file-archive',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}