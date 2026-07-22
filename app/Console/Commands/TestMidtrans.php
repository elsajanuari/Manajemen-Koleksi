<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:test-midtrans')]
#[Description('Command description')]
class TestMidtrans extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Test storeStep1
        \Illuminate\Support\Facades\Auth::loginUsingId(1);
        $request = new \Illuminate\Http\Request();
        $request->merge(['rental_type' => 'perseorangan']);
        $controller = new \App\Http\Controllers\PenyewaanController();
        $painting = \App\Models\Painting::find(10);

        try {
            $response = $controller->storeStep1($request, $painting);
            $this->info('Success: storeStep1 completed');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
