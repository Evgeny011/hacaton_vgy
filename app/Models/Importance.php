<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'level',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}