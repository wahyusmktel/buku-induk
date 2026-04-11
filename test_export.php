<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$bukuInduk = App\Models\BukuInduk::first();
$export = new App\Exports\PrestasiTemplateExport($bukuInduk);
Maatwebsite\Excel\Facades\Excel::store($export, 'test_export.xlsx', 'local');
echo 'Exported test_export.xlsx';
?>
