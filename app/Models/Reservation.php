<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'reserved_date',
        'expired_date',
        'status',
        'notified_at',
        'notes',
    ];

    protected $casts = [
        'reserved_date' => 'date',
        'expired_date' => 'date',
        'notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'ready']);
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_date', '<', Carbon::today())
                     ->where('status', 'pending');
    }

    public function isExpired()
    {
        return $this->expired_date < Carbon::today() && $this->status === 'pending';
    }
}
