<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'member_card_number',
        'name',
        'email',
        'password',
        'role',
        'member_type',
        'max_loan',
        'loan_period_days',
        'member_since',
        'member_expired_at',
        'institution',
        'student_id',
        'occupation',
        'birth_date',
        'gender',
        'status',
        'phone',
        'address',
        'id_card',
        'photo',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'member_since' => 'date',
        'member_expired_at' => 'date',
        'birth_date' => 'date',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function activeBorrowings()
    {
        return $this->hasMany(Borrowing::class)->whereIn('status', ['pending', 'approved']);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isMembershipExpired()
    {
        if (!$this->member_expired_at) return false;
        return Carbon::today()->greaterThan($this->member_expired_at);
    }

    public function canBorrow()
    {
        if (!$this->isActive()) return false;
        if ($this->isMembershipExpired()) return false;
        
        $currentLoans = $this->activeBorrowings()->count();
        return $currentLoans < $this->max_loan;
    }

    public function getRemainingLoanQuotaAttribute()
    {
        $currentLoans = $this->activeBorrowings()->count();
        return max(0, $this->max_loan - $currentLoans);
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) return null;
        return Carbon::parse($this->birth_date)->age;
    }

    public function generateMemberCardNumber()
    {
        $prefix = strtoupper(substr($this->member_type, 0, 3));
        $year = date('y');
        $lastMember = User::where('member_card_number', 'like', "$prefix-$year-%")
                          ->orderBy('member_card_number', 'desc')
                          ->first();
        
        $number = $lastMember ? (int)substr($lastMember->member_card_number, -4) + 1 : 1;
        return sprintf("%s-%s-%04d", $prefix, $year, $number);
    }
}