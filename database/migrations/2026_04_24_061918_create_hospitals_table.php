<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id(); 

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('phone', 20);
            $table->string('city', 100);
            $table->string('district', 100);
            $table->string('license_number', 100)->unique();

            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])
                  ->default('pending');

            $table->timestamp('approved_at')->nullable(); 
            $table->rememberToken(); 
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};