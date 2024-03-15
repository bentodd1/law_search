<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Employee extends Model
{
    use Searchable;

    use HasFactory;

    protected $fillable = ['name', 'correspondent_id'];

    public function correspondent()
    {
        return $this->belongsTo(Correspondent::class);
    }

    public function caseFileHeaders()
    {
        return $this->hasMany(CaseFileHeader::class);
    }

    public function toSearchableArray()
    {
        return ['name' => $this->name];
    }

}
