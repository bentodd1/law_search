<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmericanCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
    ];

    public function classifications()
    {
        return $this->belongsToMany(Classification::class, 'classification_american_code');
    }


}
