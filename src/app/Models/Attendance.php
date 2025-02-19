<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BreakModel;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'approval_status',
        'clock_in',
        'clock_out',
        'remarks'
    ];

    public const STATUS_OFF_DUTY = '勤務外';
    public const STATUS_CLOCKED_IN = '出勤中';
    public const STATUS_ON_BREAK = '休憩中';
    public const STATUS_CLOCKED_OUT = '退勤済';

    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 休憩リレーション（1つのAttendanceに対し、複数のBreakModel）
     */
    public function breaks()
    {
        return $this->hasMany(BreakModel::class);
    }

    /**
     * 休憩時間（分）を計算
     */
    public function getBreakTime()
    {
        return $this->breaks->sum(function ($break) {
            if ($break->break_start && $break->break_end) {
                return Carbon::parse($break->break_start)->diffInMinutes(Carbon::parse($break->break_end));
            }
            return 0;
        });
    }

    public function getFormattedBreakTimes()
    {
        // 休憩データを取得
        $breaks = $this->breaks;

        // フォーマットして返す
        return $breaks->map(function ($break) {
            return [
                'id' => $break['id'],  // 配列形式の場合、`$break->id` ではなく `$break['id']` とする
                'break_start' => $break['break_start'] ? \Carbon\Carbon::parse($break['break_start'])->format('H:i') : '',
                'break_end' => $break['break_end'] ? \Carbon\Carbon::parse($break['break_end'])->format('H:i') : '',
            ];
        });
    }

    /**
     * 休憩時間を「h:mm」形式で取得
     */
    public function getFormattedBreakTime()
    {
        $breakMinutes = $this->getBreakTime();
        $breakHours = floor($breakMinutes / 60);
        $breakMinutes = $breakMinutes % 60;

        return intval($breakHours) . ':' . str_pad(intval($breakMinutes), 2, '0', STR_PAD_LEFT);
    }

    /**
     * 勤務時間を「h:mm」形式で取得
     */
    public function getWorkTime()
    {
        if ($this->clock_in && $this->clock_out) {
            $workMinutes = Carbon::parse($this->clock_in)->diffInMinutes(Carbon::parse($this->clock_out));
            $actualWorkMinutes = $workMinutes - $this->getBreakTime();
            $workHours = floor($actualWorkMinutes / 60);
            $workMinutes = $actualWorkMinutes % 60;

            return intval($workHours) . ':' . str_pad(intval($workMinutes), 2, '0', STR_PAD_LEFT);
        }
        return '-';
    }

    /**
     * 日付のフォーマットを変更（mm/dd(D)）
     */
    public function getFormattedDate()
    {
        return Carbon::parse($this->clock_in)->translatedFormat('m/d(D)');
    }

    /**
     * 出勤時間フォーマット
     */
    public function getFormattedClockIn()
    {
        return $this->clock_in ? Carbon::parse($this->clock_in)->format('H:i') : 'ー';
    }

    /**
     * 退勤時間フォーマット
     */
    public function getFormattedClockOut()
    {
        return $this->clock_out ? Carbon::parse($this->clock_out)->format('H:i') : 'ー';
    }

    /**
     * 年を取得
     */
    public function getYearFromClockInAttribute()
    {
        return Carbon::parse($this->clock_in)->format('Y年');
    }

    /**
     * 月日を取得
     */
    public function getMonthDayFromClockInAttribute()
    {
        return Carbon::parse($this->clock_in)->format('n月j日');
    }
}
