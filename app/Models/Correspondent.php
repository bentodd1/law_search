<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Lawyer at USPTO
class Correspondent extends Model
{
    use HasFactory;

    public function caseFiles()
    {
        return $this->hasMany(CaseFile::class);
    }
}
