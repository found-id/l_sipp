<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = \Illuminate\Support\Facades\DB::select("DESCRIBE mitra");
$fields = ['jarak', 'honor', 'fasilitas', 'kesesuaian_jurusan', 'tingkat_kebersihan'];

foreach ($columns as $col) {
    if (in_array($col->Field, $fields)) {
        echo $col->Field . ": " . $col->Null . "\n";
    }
}
