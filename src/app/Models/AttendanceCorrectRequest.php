<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceCorrectRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'clock_in',
        'clock_out',
        'remarks',
        'status',
    ];

    public const STATUS_PENDING = '承認待ち';
    public const STATUS_APPROVED = '承認済み';

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function breakCorrectRequests()
    {
        return $this->hasMany(BreakCorrectRequest::class);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('attendance', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function formatClockIn()
    {
        return $this->clock_in ? Carbon::parse($this->clock_in)->format('H:i') : '';
    }

    public function formatClockOut()
    {
        return $this->clock_out ? Carbon::parse($this->clock_out)->format('H:i') : '';
    }
}
