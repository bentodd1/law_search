<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'registration_number',
        'transaction_date',
    ];

    public function header()
    {
        return $this->hasOne(CaseFileHeader::class);
    }

    public function statements()
    {
        return $this->hasMany(CaseFileStatement::class);
    }

    public function classifications()
    {
        return $this->belongsToMany(Classification::class, 'case_file_classification');
    }

    public function correspondent()
    {
        return $this->belongsTo(Correspondent::class);
    }

    public function caseFileOwners()
    {
        return $this->belongsToMany(CaseFileOwner::class, 'case_file_owner_case_file');
    }
}
