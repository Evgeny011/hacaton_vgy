<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'category',
        'icon',
        'extension',
        'mime_type',
        'max_size',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'max_size' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the documents for the document type.
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'type_id');
    }

    /**
     * Scope a query to only include active document types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the human readable file size limit.
     */
    public function getFormattedMaxSizeAttribute()
    {
        if ($this->max_size >= 1048576) {
            return round($this->max_size / 1048576, 2) . ' MB';
        } elseif ($this->max_size >= 1024) {
            return round($this->max_size / 1024, 2) . ' KB';
        } else {
            return $this->max_size . ' bytes';
        }
    }

    /**
     * Check if the file size is within limits.
     */
    public function isSizeValid($fileSize)
    {
        return $fileSize <= $this->max_size;
    }

    /**
     * Get allowed extensions as array.
     */
    public function getAllowedExtensionsArrayAttribute()
    {
        return array_map('trim', explode(',', $this->extension));
    }

    /**
     * Check if extension is allowed for this document type.
     */
    public function isExtensionAllowed($extension)
    {
        $allowedExtensions = $this->allowed_extensions_array;
        return in_array(strtolower($extension), $allowedExtensions);
    }

    /**
     * Get the icon HTML with proper fallback.
     */
    public function getIconHtmlAttribute()
    {
        $icon = $this->icon ?: 'file';
        return '<i class="fas fa-' . $icon . '"></i>';
    }

    /**
     * Get display name with icon.
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->extension . ')';
    }

    /**
     * Get category display name.
     */
    public function getCategoryDisplayNameAttribute()
    {
        return match($this->category) {
            'main' => 'Основные документы',
            'additional' => 'Дополнительные документы',
            default => 'Другие документы'
        };
    }

    /**
     * Check if this is a main category document type.
     */
    public function getIsMainCategoryAttribute()
    {
        return $this->category === 'main';
    }

    /**
     * Check if this is an additional category document type.
     */
    public function getIsAdditionalCategoryAttribute()
    {
        return $this->category === 'additional';
    }
}