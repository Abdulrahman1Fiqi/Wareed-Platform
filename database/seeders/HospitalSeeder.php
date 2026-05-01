<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $data  = $this->loadJson();
        $admin = User::where('role', 'admin')->first();

        foreach ($data['hospitals'] as $row) {

            $row['password'] = env('HOSPITAL_PASSWORD');

            Hospital::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name'           => $row['name'],
                    'password'       => Hash::make($row['password']),
                    'phone'          => $row['phone'],
                    'city'           => $row['city'],
                    'district'       => $row['district'],
                    'license_number' => $row['license_number'],
                    'status'         => $row['status'],
                    'approved_by'    => $row['status'] === 'approved' ? $admin?->id : null,
                    'approved_at'    => $row['status'] === 'approved' ? now()->subDays(30) : null,
                ]
            );
        }

        $this->command->info('✅ Hospitals seeded (' . count($data['hospitals']) . ')');
    }

    private function loadJson(): array
    {
        $path = database_path('seeders/data/wareed_seed_data.json');
        return json_decode(file_get_contents($path), true);
    }
}
