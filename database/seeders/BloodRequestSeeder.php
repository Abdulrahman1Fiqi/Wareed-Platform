<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodRequestSeeder extends Seeder
{
    private array $compatibility = [
        'A+'  => ['A+', 'A-', 'O+', 'O-'],
        'A-'  => ['A-', 'O-'],
        'B+'  => ['B+', 'B-', 'O+', 'O-'],
        'B-'  => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'O+'  => ['O+', 'O-'],
        'O-'  => ['O-'],
    ];

    private array $expiryHours = [
        'critical' => 6,
        'urgent'   => 24,
        'standard' => 72,
    ];

    public function run(): void
    {
        $data      = $this->loadJson();
        $hospitals = Hospital::where('status', 'approved')->get();

        if ($hospitals->isEmpty()) {
            $this->command->warn('⚠️  No approved hospitals found — skipping blood requests');
            return;
        }

        foreach ($data['blood_requests'] as $row) {
            $hospital = $hospitals[$row['hospital_index']] ?? $hospitals->first();
            $daysAgo  = $row['days_ago'];

            $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 12));
            $expiresAt = (clone $createdAt)->addHours($this->expiryHours[$row['urgency']]);

            $request = BloodRequest::create([
                'hospital_id'    => $hospital->id,
                'blood_type'     => $row['blood_type'],
                'units_needed'   => $row['units_needed'],
                'urgency'        => $row['urgency'],
                'status'         => $row['status'],
                'contact_person' => $row['contact_person'],
                'contact_phone'  => $row['contact_phone'],
                'notes'          => $row['notes'],
                'expires_at'     => $expiresAt,
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);

            $this->seedResponses($request, $hospital->city);
        }

        $this->command->info('✅ Blood requests seeded (' . count($data['blood_requests']) . ')');
    }

    private function seedResponses(BloodRequest $request, string $city): void
    {
        $compatibleTypes = $this->compatibility[$request->blood_type] ?? [];

        $donors = User::where('role', 'donor')
            ->where('city', $city)
            ->whereIn('blood_type', $compatibleTypes)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        if ($donors->isEmpty()) {
            $donors = User::where('role', 'donor')
                ->whereIn('blood_type', $compatibleTypes)
                ->inRandomOrder()
                ->limit(3)
                ->get();
        }

        foreach ($donors as $index => $donor) {
            $status = $this->resolveResponseStatus($request->status, $index);

            if ($status === null) continue;

            $respondedAt = $status !== 'notified'
                ? $request->created_at->addMinutes(rand(5, 120))
                : null;

            $confirmedAt = $status === 'confirmed'
                ? $respondedAt->addHours(rand(1, 4))
                : null;

            DonorResponse::firstOrCreate(
                [
                    'blood_request_id' => $request->id,
                    'donor_id'         => $donor->id,
                ],
                [
                    'status'           => $status,
                    'responded_at'     => $respondedAt,
                    'confirmed_at'     => $confirmedAt,
                    'created_at'       => $request->created_at,
                    'updated_at'       => $respondedAt ?? $request->created_at,
                ]
            );
        }
    }

    private function resolveResponseStatus(string $requestStatus, int $donorIndex): ?string
    {
        return match ($requestStatus) {
            'active'              => match ($donorIndex) {
                0, 1    => 'accepted',
                2       => 'declined',
                default => 'notified',
            },
            'partially_fulfilled' => match ($donorIndex) {
                0       => 'confirmed',
                1       => 'accepted',
                2, 3    => 'declined',
                default => 'notified',
            },
            'fulfilled'           => match ($donorIndex) {
                0, 1    => 'confirmed',
                2       => 'accepted',
                3       => 'declined',
                default => null,
            },
            'expired'             => match ($donorIndex) {
                0, 1    => 'declined',
                default => 'notified',
            },
            'cancelled'           => null,
            default               => null,
        };
    }

    private function loadJson(): array
    {
        $path = database_path('seeders/data/wareed_seed_data.json');
        return json_decode(file_get_contents($path), true);
    }
}
