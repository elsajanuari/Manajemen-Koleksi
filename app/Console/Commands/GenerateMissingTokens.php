<?php

namespace App\Console\Commands;

use App\Models\DetailPengunjung;
use Illuminate\Console\Command;

class GenerateMissingTokens extends Command
{
    protected $signature = 'tokens:generate';
    protected $description = 'Generate missing verification tokens for detail pengunjungs';

    public function handle()
    {
        $details = DetailPengunjung::whereNull('tiket_verifikasi_token')->get();
        
        $count = 0;
        foreach ($details as $detail) {
            $detail->tiket_verifikasi_token = $this->generateUniqueToken();
            $detail->save();
            $count++;
            $this->info("Generated token for ID: {$detail->id}");
        }
        
        $this->info("Done! Generated {$count} tokens.");
    }
    
    private function generateUniqueToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32));
            $exists = DetailPengunjung::where('tiket_verifikasi_token', $token)->exists();
        } while ($exists);
        
        return $token;
    }
}