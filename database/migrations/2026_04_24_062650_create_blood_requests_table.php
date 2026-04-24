<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();

            // which hospital created this request?
            // cascadeOnDelete() means if the hospital is deleted,
            // all their requests are deleted too — makes sense
            $table->foreignId('hospital_id')
                  ->constrained('hospitals')
                  ->cascadeOnDelete();

            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->unsignedInteger('units_needed')->default(1);

            $table->enum('urgency', ['critical', 'urgent', 'standard'])
                  ->default('urgent');

            $table->enum('status', [
                'active',
                'partially_fulfilled',
                'fulfilled',
                'expired',
                'cancelled',
            ])->default('active');

            $table->string('contact_person', 100);
            $table->string('contact_phone', 20);
            $table->text('notes')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(['blood_type', 'status']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};