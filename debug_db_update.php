<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE mitra MODIFY COLUMN jarak DECIMAL(8, 2) NULL');
    echo "Success: jarak modified\n";
    
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE mitra MODIFY COLUMN honor TINYINT NULL');
    echo "Success: honor modified\n";
    
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE mitra MODIFY COLUMN fasilitas TINYINT NULL');
    echo "Success: fasilitas modified\n";

    \Illuminate\Support\Facades\DB::statement('ALTER TABLE mitra MODIFY COLUMN kesesuaian_jurusan TINYINT NULL');
    echo "Success: kesesuaian_jurusan modified\n";

    \Illuminate\Support\Facades\DB::statement('ALTER TABLE mitra MODIFY COLUMN tingkat_kebersihan TINYINT NULL');
    echo "Success: tingkat_kebersihan modified\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
