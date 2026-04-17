<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Vendor;
use App\Services\NeptunePlayService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(NeptunePlayService::class);
        $vendors = Vendor::all();

        if ($vendors->isEmpty()) {
            $this->command->warn('No vendors found. Run VendorSeeder first.');
            return;
        }

        $totalGames = 0;

        foreach ($vendors as $vendor) {
            $this->command->info("Fetching games for vendor: {$vendor->name} ({$vendor->vendor_code})...");

            try {
                $result = $service->gameList($vendor->vendor_code);
            } catch (\Throwable $e) {
                $this->command->warn("  Failed to fetch games for {$vendor->vendor_code}: " . $e->getMessage());
                continue;
            }

            // Find the games array in the response (key may vary)
            $games = null;
            foreach ($result as $value) {
                if (is_array($value) && !empty($value) && isset($value[0]['gameCode'])) {
                    $games = $value;
                    break;
                }
            }

            if (!$games) {
                $this->command->warn("  No games found for {$vendor->vendor_code}");
                continue;
            }

            $count = 0;

            foreach ($games as $game) {
                $gameCode = $game['gameCode'] ?? $game['game_code'] ?? null;
                $gameName = $game['gameName'] ?? $game['game_name'] ?? null;

                if (!$gameCode || !$gameName) {
                    continue;
                }

                Game::updateOrCreate(
                    [
                        'vendor_code' => $vendor->vendor_code,
                        'game_code' => $gameCode,
                    ],
                    [
                        'provider' => $game['provider'] ?? $vendor->name,
                        'game_id' => $game['gameId'] ?? $game['game_id'] ?? null,
                        'game_name' => $gameName,
                        'slug' => Str::slug($gameName),
                        'thumbnail' => $game['thumbnail'] ?? $game['imageUrl'] ?? $game['image'] ?? null,
                        'is_new' => (bool) ($game['isNew'] ?? $game['is_new'] ?? false),
                        'under_maintenance' => (bool) ($game['underMaintenance'] ?? $game['under_maintenance'] ?? false),
                    ]
                );

                $count++;
            }

            $totalGames += $count;
            $this->command->info("  Seeded {$count} games for {$vendor->vendor_code}.");
        }

        $this->command->info("Total games seeded: {$totalGames}");
    }
}
