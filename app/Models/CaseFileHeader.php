<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFileHeader extends Model
{
    use HasFactory;


    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class);
    }

    protected $fillable = [
        'case_file_id',
        'filing_date',
        'status_code',
        'status_date',
        'mark_identification',
        'mark_drawing_code',
        'published_for_opposition_date',
        'attorney_docket_number',
        'attorney_name',
        // ... other fields
    ];
}
