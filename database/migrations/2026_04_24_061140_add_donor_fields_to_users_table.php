<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['donor', 'admin'])->default('donor')->after('email');

            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])
                  ->nullable()
                  ->after('role');

            $table->string('phone', 20)->nullable()->after('blood_type');
            $table->string('city', 100)->nullable()->after('phone');
            $table->string('district', 100)->nullable()->after('city');

            $table->enum('status', ['available', 'unavailable', 'on_cooldown'])
                  ->default('available')
                  ->after('district');

            $table->date('last_donation_date')->nullable()->after('status');

            $table->unsignedInteger('donation_count')->default(0)->after('last_donation_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'blood_type',
                'phone',
                'city',
                'district',
                'status',
                'last_donation_date',
                'donation_count',
            ]);
        });
    }
};