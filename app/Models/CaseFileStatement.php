<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseFileStatement extends Model
{
    protected $fillable = [
        'case_file_id',
        'type_code',
        'text',
        // ... other fields as needed
    ];

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class);
    }
}
