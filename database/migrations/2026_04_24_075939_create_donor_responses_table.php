<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donor_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blood_request_id')
                  ->constrained('blood_requests')
                  ->cascadeOnDelete();

            $table->foreignId('donor_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->enum('status', ['notified', 'accepted', 'declined', 'confirmed'])
                  ->default('notified');

            $table->timestamp('responded_at')->nullable(); 
            $table->timestamp('confirmed_at')->nullable(); 

            $table->timestamps();

            $table->unique(['blood_request_id', 'donor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donor_responses');
    }
};