<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->string('path');
            $table->integer('size');
            $table->foreignId('importance_id')->nullable()->constrained();
            $table->foreignId('type_id')->constrained('document_types');
            $table->foreignId('counterparty_id')->nullable()->constrained();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['type_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};