<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakCorrectRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_correct_request_id',
        'break_id',
        'break_start',
        'break_end',
    ];

    protected $casts = [
        'break_start' => 'datetime',
        'break_end' => 'datetime',
    ];

    public function attendanceCorrectionRequest()
    {
        return $this->belongsTo(AttendanceCorrectRequest::class);
    }

    public function break()
    {
        return $this->belongsTo(BreakModel::class, 'break_id');
    }
}
