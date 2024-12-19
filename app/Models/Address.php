<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'complement',
        'city',
        'state',
        'cep',
        'latitude',
        'longitude',
    ];

    // Relacionamento com o modelo Contact
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}

