<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'fine',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getDaysBorrowedAttribute()
    {
        if ($this->return_date) {
            return $this->borrow_date->diffInDays($this->return_date);
        }
        return $this->borrow_date->diffInDays(now());
    }

    public function isOverdue()
    {
        return $this->status === 'approved' && now()->greaterThan($this->due_date);
    }
}