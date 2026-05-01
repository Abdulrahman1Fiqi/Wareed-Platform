<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('donor')->after('email');

            $table->string('blood_type', 5)
                  ->nullable()
                  ->after('role');

            $table->string('phone', 20)->nullable()->after('blood_type');
            $table->string('city', 100)->nullable()->after('phone');
            $table->string('district', 100)->nullable()->after('city');

            $table->string('status', 30)
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