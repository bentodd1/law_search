<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Person filing the case
class CaseFileOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_number', 'party_type', 'country', 'legal_entity_type_code',
        'entity_statement', 'party_name', 'address_1', 'city', 'postcode'
    ];

    public function caseFiles()
    {
        return $this->belongsToMany(CaseFile::class, 'case_file_owner_case_file');
    }

}


