<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $t = App\Models\PlannedTraining::where('source', 'Generated from TNA')->latest()->first();
    if (!$t) {
        echo "No TNA-generated training found\n";
        exit;
    }
    echo "Training: {$t->id} staff_id=" . var_export($t->staff_id, true) . " participants={$t->participants()->count()} source={$t->source}\n";
    $t->delete();
    echo "DELETED OK\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
