<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;

class MyImport implements ToArray {
    public function array(array $array) {}
}

try {
    $data = Excel::toArray(new MyImport, 'c:\\Projects\\buku-induk\\template-dapodik-siswa\\Daftar Nilai Buku Induk (1).xlsx');
    // Print first sheet, first 100 rows
    $sheet = $data[0] ?? [];
    foreach(array_slice($sheet, 0, 100) as $rowIndex => $row) {
        echo "Row $rowIndex: ";
        foreach(array_slice($row, 0, 20) as $col) {
            echo ($col ?? 'NULL') . " | ";
        }
        echo "\n";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
