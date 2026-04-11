<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            ['key' => 'paper_size', 'value' => 'a4'],
            ['key' => 'paper_width', 'value' => '210'],
            ['key' => 'paper_height', 'value' => '297'],
            ['key' => 'margin_top', 'value' => '2.5'],
            ['key' => 'margin_right', 'value' => '2.5'],
            ['key' => 'margin_bottom', 'value' => '2.5'],
            ['key' => 'margin_left', 'value' => '2.5'],
        ];

        foreach ($settings as $setting) {
            \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('settings')->whereIn('key', [
            'paper_size', 'paper_width', 'paper_height',
            'margin_top', 'margin_right', 'margin_bottom', 'margin_left'
        ])->delete();
    }
};
