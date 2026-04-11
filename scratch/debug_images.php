<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$imageKeys = ['sekolah_kop', 'sekolah_logo', 'kepsek_ttd', 'sekolah_stempel'];
$settings = Setting::pluck('value', 'key')->toArray();

foreach ($imageKeys as $key) {
    echo "Key: $key\n";
    if (!empty($settings[$key])) {
        $val = $settings[$key];
        echo "  Value: $val\n";
        $path1 = public_path('storage/' . $val);
        $path2 = storage_path('app/public/' . $val);
        
        echo "  Path 1 (public): $path1 - " . (file_exists($path1) ? "EXISTS" : "MISSING") . "\n";
        echo "  Path 2 (storage): $path2 - " . (file_exists($path2) ? "EXISTS" : "MISSING") . "\n";
        
        if (file_exists($path2)) {
            $data = file_get_contents($path2);
            echo "  Data size: " . strlen($data) . " bytes\n";
            $ext = strtolower(pathinfo($path2, PATHINFO_EXTENSION));
            echo "  Extension: $ext\n";
        }
    } else {
        echo "  Value is empty\n";
    }
    echo "-------------------\n";
}
