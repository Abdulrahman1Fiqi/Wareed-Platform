<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DonorSeeder extends Seeder
{
    public function run(): void
    {
        $data = $this->loadJson();

        foreach ($data['donors'] as $row) {

            $row['password'] = env('USER_PASSWORD');

            User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name'               => $row['name'],
                    'password'           => Hash::make($row['password']),
                    'role'               => 'donor',
                    'phone'              => $row['phone'],
                    'blood_type'         => $row['blood_type'],
                    'city'               => $row['city'],
                    'district'           => $row['district'],
                    'status'             => $row['status'],
                    'donation_count'     => $row['donation_count'],
                    'last_donation_date' => $row['last_donation_date'],
                    'email_verified_at'  => now(),
                ]
            );
        }

        $this->command->info('✅ Donors seeded (' . count($data['donors']) . ')');
    }

    private function loadJson(): array
    {
        $path = database_path('seeders/data/wareed_seed_data.json');
        return json_decode(file_get_contents($path), true);
    }
}
