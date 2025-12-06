<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'call_number',
        'ddc',
        'title',
        'author',
        'isbn',
        'publisher', // Keep old field for backward compatibility
        'publisher_id',
        'publication_year',
        'language',
        'edition',
        'pages',
        'series',
        'subjects',
        'physical_description',
        'category', // Keep old field for backward compatibility
        'category_id',
        'location_id',
        'shelf_position',
        'collection_type',
        'total_copies',
        'available_copies',
        'status',
        'description',
        'cover_image',
        'price',
        'source',
        'acquisition_date',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function publisherModel()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function isAvailable()
    {
        return $this->available_copies > 0 && $this->status === 'available';
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0)
                     ->where('status', 'available');
    }

    public function scopeCirculation($query)
    {
        return $query->where('collection_type', 'circulation');
    }

    public function getFullCallNumberAttribute()
    {
        return trim(($this->ddc ?? '') . ' ' . ($this->call_number ?? ''));
    }

    public function getBorrowedCopiesAttribute()
    {
        return $this->total_copies - $this->available_copies;
    }
}