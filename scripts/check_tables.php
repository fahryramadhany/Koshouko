<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = ['sessions','reviews','borrowings','books'];
foreach ($tables as $t) {
    echo $t . ': ' . (Schema::hasTable($t) ? 'exists' : 'missing') . PHP_EOL;
    if (Schema::hasTable($t)) {
        $count = DB::table($t)->limit(1)->count();
        echo "  sample_count: $count\n";
    }
}
