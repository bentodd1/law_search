<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
    ];

    public function caseFiles()
    {
        return $this->belongsToMany(CaseFile::class, 'case_file_classification');
    }

    public function usCodes()
    {
        return $this->belongsToMany(Classification::class, 'classification_us_code');
    }

}

