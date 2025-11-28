<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('document_types', function (Blueprint $table) {
            $table->string('category')->default('main')->after('description');
            $table->string('icon')->default('file')->after('category');
        });
    }

    public function down()
    {
        Schema::table('document_types', function (Blueprint $table) {
            $table->dropColumn(['category', 'icon']);
        });
    }
};