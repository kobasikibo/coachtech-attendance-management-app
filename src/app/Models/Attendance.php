<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'clock_in', 'clock_out', 'break_start', 'break_end'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBreakTime()
    {
        if ($this->break_start && $this->break_end) {
            $breakMinutes = Carbon::parse($this->break_start)->diffInMinutes(Carbon::parse($this->break_end));
            return $breakMinutes;
        }
        return 0;
    }

    public function getFormattedBreakTime()
    {
        $breakMinutes = $this->getBreakTime();
        $breakHours = floor($breakMinutes / 60);
        $breakMinutes = $breakMinutes % 60;

        return intval($breakHours) . ':' . str_pad(intval($breakMinutes), 2, '0', STR_PAD_LEFT);
    }

    public function getWorkTime()
    {
        if ($this->clock_in && $this->clock_out) {
            $workMinutes = Carbon::parse($this->clock_in)->diffInMinutes(Carbon::parse($this->clock_out));
            $actualWorkMinutes = $workMinutes - $this->getBreakTime();
            $workHours = floor($actualWorkMinutes / 60);
            $workMinutes = $actualWorkMinutes % 60;

            return intval($workHours) . ':' . str_pad(intval($workMinutes), 2, '0', STR_PAD_LEFT);
        }
        return 'ー';
    }

    public function getFormattedDate()
    {
        return Carbon::parse($this->clock_in)->translatedFormat('m/d(D)');
    }

    public function getFormattedClockIn()
    {
        return $this->clock_in ? Carbon::parse($this->clock_in)->format('H:i') : 'ー';
    }

    public function getFormattedClockOut()
    {
        return $this->clock_out ? Carbon::parse($this->clock_out)->format('H:i') : 'ー';
    }

    public function getFormattedBreakStart()
    {
        return $this->break_start ? Carbon::parse($this->break_start)->format('H:i') : 'ー';
    }

    public function getFormattedBreakEnd()
    {
        return $this->break_end ? Carbon::parse($this->break_end)->format('H:i') : 'ー';
    }

    public function getYearFromClockInAttribute()
    {
        return Carbon::parse($this->clock_in)->format('Y年');
    }

    public function getMonthDayFromClockInAttribute()
    {
        return Carbon::parse($this->clock_in)->format('n月j日');
    }
}
