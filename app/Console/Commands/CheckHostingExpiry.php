<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\FirebaseService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckHostingExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hosting:check-expiry';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Check hosting expiry dates and send notifications';

    /**
     * Firebase Service instance
     */
    private FirebaseService $firebaseService;

    /**
     * Create a new command instance.
     */
    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->firebaseService = $firebaseService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Checking hosting expiry dates...');

        try {
            // Get all clients
            $clients = Client::all();

            $this->info("📊 Total clients: {$clients->count()}");

            $notified = 0;
            $expired = 0;
            $urgent = 0;

            foreach ($clients as $client) {
                if ($client->isExpired()) {
                    $expired++;

                    if ($client->fcm_token) {
                        $this->firebaseService->sendNotification(
                            $client->fcm_token,
                            '❌ HOSTING SUDAH EXPIRED!',
                            "Hosting untuk {$client->name} ({$client->website}) sudah kadaluarsa sejak {$client->expiry_date->format('d M Y')}. Segera lakukan perpanjangan!",
                            [
                                'client_id' => $client->id,
                                'status' => 'expired',
                                'website' => $client->website
                            ]
                        );
                        $notified++;
                    }

                    $this->warn("❌ {$client->name} - EXPIRED ({$client->days_remaining} hari)");
                }
                elseif ($client->isUrgent()) {
                    $urgent++;

                    if ($client->fcm_token) {
                        $this->firebaseService->sendNotification(
                            $client->fcm_token,
                            '🔴 HOSTING AKAN SEGERA EXPIRED!',
                            "Hosting untuk {$client->name} ({$client->website}) akan kadaluarsa dalam {$client->days_remaining} hari pada {$client->expiry_date->format('d M Y')}. Segera lakukan perpanjangan!",
                            [
                                'client_id' => $client->id,
                                'status' => 'urgent',
                                'days_remaining' => $client->days_remaining,
                                'website' => $client->website
                            ]
                        );
                        $notified++;
                    }

                    $this->warn("🔴 {$client->name} - URGENT ({$client->days_remaining} hari)");
                }
                elseif ($client->needsAttention()) {
                    if ($client->fcm_token) {
                        $this->firebaseService->sendNotification(
                            $client->fcm_token,
                            '🟡 PERHATIAN - Hosting akan segera kadaluarsa',
                            "Hosting untuk {$client->name} ({$client->website}) akan kadaluarsa dalam {$client->days_remaining} hari. Siapkan perpanjangan sekarang.",
                            [
                                'client_id' => $client->id,
                                'status' => 'attention',
                                'days_remaining' => $client->days_remaining,
                                'website' => $client->website
                            ]
                        );
                        $notified++;
                    }

                    $this->info("🟡 {$client->name} - ATTENTION ({$client->days_remaining} hari)");
                }
                else {
                    $this->line("✅ {$client->name} - SAFE ({$client->days_remaining} hari)");
                }
            }

            // Summary
            $this->info("\n📋 RINGKASAN:");
            $this->info("   Total Expired: {$expired}");
            $this->info("   Total Urgent: {$urgent}");
            $this->info("   Notifikasi Terkirim: {$notified}");
            $this->info("✓ Pemeriksaan selesai!\n");

            return Command::SUCCESS;
        }
        catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
