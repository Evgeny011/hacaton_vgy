<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'mime_type',
        'path',
        'size',
        'importance_id',
        'type_id',
        'counterparty_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'type_id');
    }

    public function importance()
    {
        return $this->belongsTo(Importance::class);
    }

    public function counterparty()
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function getFileSizeFormatted()
    {
        $size = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }

    public function getFileIcon()
    {
        $mime = $this->mime_type;
        
        if (str_contains($mime, 'image')) {
            return '📷';
        } elseif (str_contains($mime, 'pdf')) {
            return '📕';
        } elseif (str_contains($mime, 'word')) {
            return '📄';
        } elseif (str_contains($mime, 'excel') || str_contains($mime, 'sheet')) {
            return '📊';
        } else {
            return '📎';
        }
    }
}