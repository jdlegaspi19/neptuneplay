<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Services\NeptunePlayService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(NeptunePlayService::class);

        $this->command->info('Fetching vendor list from NeptunePlay API...');

        try {
            $result = $service->vendorList();
        } catch (\Throwable $e) {
            $this->command->error('Failed to fetch vendor list: ' . $e->getMessage());
            return;
        }

        // Find the vendors array in the response (key may vary)
        $vendors = null;
        foreach ($result as $value) {
            if (is_array($value) && !empty($value) && isset($value[0]['vendorCode'])) {
                $vendors = $value;
                break;
            }
        }

        if (!$vendors) {
            $this->command->error('Could not find vendors in API response.');
            Log::error('VendorSeeder: unexpected response', $result);
            return;
        }

        $count = 0;

        foreach ($vendors as $vendor) {
            $vendorCode = $vendor['vendorCode'] ?? $vendor['vendor_code'] ?? null;
            $name = $vendor['vendorName'] ?? $vendor['name'] ?? $vendorCode;
            $type = $vendor['type'] ?? $vendor['gameType'] ?? 2;
            $url = $vendor['url'] ?? $vendor['imageUrl'] ?? null;
            $underMaintenance = $vendor['underMaintenance'] ?? $vendor['under_maintenance'] ?? false;

            if (!$vendorCode) {
                $this->command->warn('Skipping vendor with no vendor code: ' . json_encode($vendor));
                continue;
            }

            Vendor::updateOrCreate(
                ['vendor_code' => $vendorCode],
                [
                    'name' => $name,
                    'type' => (int) $type,
                    'url' => $url,
                    'under_maintenance' => (bool) $underMaintenance,
                ]
            );

            $count++;
        }

        $this->command->info("Seeded {$count} vendors successfully.");
    }
}
