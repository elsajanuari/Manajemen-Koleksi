<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\TicketQuota;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncHolidays extends Command
{
    protected $signature = 'holidays:sync {--year= : Specific year to sync (default: current and next year)}';
    protected $description = 'Sync Indonesian national holidays from Calendarific API and regenerate ticket quotas';

    public function handle()
    {
        $this->info('🔄 Starting holiday sync...');
        
        $currentYear = now()->year;
        $years = $this->option('year') 
            ? [(int) $this->option('year')] 
            : [$currentYear, $currentYear + 1, $currentYear + 2];
        
        $results = [];
        
        foreach ($years as $year) {
            $this->info("Fetching holidays for year {$year}...");
            
            // Clear cache
            TicketQuota::clearHolidayCache($year);
            // Get fresh data
            $holidays = TicketQuota::getIndonesianHolidays($year);
            $results[$year] = count($holidays);
            
            $this->line("  ✓ Found {$results[$year]} holidays for {$year}");
        }
        
        // Save last sync time
        Cache::put('holidays_last_sync', now(), 60 * 24 * 365);
        
        // Regenerate quotas for all active tickets
        $this->info('Regenerating quotas for all active tickets...');
        
        $tickets = Ticket::where('status', true)->get();
        $count = 0;
        $errors = [];
        
        $bar = $this->output->createProgressBar(count($tickets));
        
        foreach ($tickets as $ticket) {
            try {
                $ticket->regenerateQuotas();
                $count++;
            } catch (\Exception $e) {
                $errors[] = "{$ticket->nama_tiket}: " . $e->getMessage();
                Log::error("Failed to regenerate quotas for ticket {$ticket->id}: " . $e->getMessage());
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("✅ Sync completed!");
        $this->info("📊 Holidays found: " . implode(', ', array_map(fn($y, $c) => "{$y}: {$c}", array_keys($results), $results)));
        $this->info("🎟️ Tickets regenerated: {$count}/" . count($tickets));
        
        if (count($errors)) {
            $this->warn("⚠️ Errors: " . implode(', ', $errors));
        }
        
        return Command::SUCCESS;
    }
}