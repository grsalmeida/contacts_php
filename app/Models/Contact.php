<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'address_id', // Relacionamento com a tabela de endereços
    ];

    // Relacionamento com o modelo Address
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}

