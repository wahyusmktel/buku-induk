<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportJob extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'total_records',
        'processed_records',
        'file_path',
        'status',
        'error_message'
    ];
}
