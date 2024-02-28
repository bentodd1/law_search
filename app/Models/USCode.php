<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class USCode extends Model
{
    protected $table = 'us_codes';

    use HasFactory;
    protected $fillable = [
        'code',
    ];

    public function classifications()
    {
        return $this->belongsToMany(Classification::class, 'classification_us_code');
    }


}
