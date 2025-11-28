<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counterparty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    // Отношения
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}