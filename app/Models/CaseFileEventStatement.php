<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFileEventStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_file_id',
        'code',
        'type',
        'description_text',
        'date',
        'number',
    ];

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class);
    }
}
