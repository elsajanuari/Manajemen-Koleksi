<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sequenceMap = [];
foreach (App\Models\Koleksi::orderBy('created_at')->get() as $koleksi) {
    $year = $koleksi->created_at ? $koleksi->created_at->format('Y') : date('Y');
    $key = $koleksi->kategori . '-' . $year;
    $sequenceMap[$key] = (isset($sequenceMap[$key]) ? $sequenceMap[$key] : 0) + 1;
    $koleksi->nomor_inventaris = App\Models\Koleksi::generateNomorInventaris($koleksi->kategori, $year, $sequenceMap[$key]);
    $koleksi->save();
}

echo "Updated existing koleksi inventory numbers.\n";
