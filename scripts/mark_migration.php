<?php
// One-off helper: check table existence and insert migration record if table exists but migration is missing.
// Usage: php scripts/mark_migration.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$migration = '2026_01_25_075314_create_sessions_table';
$table = 'sessions';

echo "Checking table and migration status for '$table' / '$migration'...\n";

$hasTable = Schema::hasTable($table);
$inMigrations = DB::table('migrations')->where('migration', $migration)->exists();

echo "table_exists=" . ($hasTable ? 'yes' : 'no') . "\n";
echo "migration_record=" . ($inMigrations ? 'yes' : 'no') . "\n";

if ($hasTable && ! $inMigrations) {
    $maxBatch = DB::table('migrations')->max('batch') ?? 0;
    $batch = $maxBatch + 1;
    DB::table('migrations')->insert([
        'migration' => $migration,
        'batch' => $batch,
    ]);
    echo "Inserted migration record for $migration with batch=$batch\n";
} elseif (! $hasTable) {
    echo "Table '$table' does not exist — do not mark migration. Run the migration instead.\n";
} else {
    echo "No action needed.\n";
}
