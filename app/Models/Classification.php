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

    public function americanCodes()
    {
        return $this->belongsToMany(AmericanCode::class, 'classification_american_code');
    }

}

