<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BreakModel;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'approval_status',
        'date',
        'clock_in',
        'clock_out',
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breaks()
    {
        return $this->hasMany(BreakModel::class)->orderBy('break_start');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForToday($query)
    {
        return $query->whereDate('date', today());
    }

    public const STATUS_OFF_DUTY = '勤務外';
    public const STATUS_CLOCKED_IN = '出勤中';
    public const STATUS_ON_BREAK = '休憩中';
    public const STATUS_CLOCKED_OUT = '退勤済';

    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';
}
