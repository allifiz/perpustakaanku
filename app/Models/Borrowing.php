<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'renewal_count',
        'renewed_at',
        'approved_by',
        'approved_at',
        'returned_to',
        'fine_amount',
        'fine_paid',
        'notes',
        'condition_on_borrow',
        'condition_on_return',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'renewed_at' => 'date',
        'approved_at' => 'datetime',
        'fine_amount' => 'decimal:2',
        'fine_paid' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returner()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    public function getDaysBorrowedAttribute()
    {
        if ($this->return_date) {
            return $this->borrow_date->diffInDays($this->return_date);
        }
        return $this->borrow_date->diffInDays(now());
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) return 0;
        
        $endDate = $this->return_date ?? Carbon::today();
        return $this->due_date->diffInDays($endDate);
    }

    public function isOverdue()
    {
        if ($this->status !== 'approved') return false;
        if ($this->return_date) return false;
        
        return Carbon::today()->greaterThan($this->due_date);
    }

    public function canRenew()
    {
        if ($this->status !== 'approved') return false;
        if ($this->renewal_count >= 2) return false; // Max 2 renewals
        if ($this->isOverdue()) return false;
        
        return true;
    }

    public function calculateFine($finePerDay = 1000)
    {
        if (!$this->isOverdue() && !$this->days_overdue) return 0;
        
        return $this->days_overdue * $finePerDay;
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'approved')
                     ->whereNull('return_date')
                     ->where('due_date', '<', Carbon::today());
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }
}