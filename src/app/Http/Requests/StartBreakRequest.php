<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attendance;

class StartBreakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [];
    }

    public function validateStartBreak()
    {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->first();

        if (!$attendance || $attendance->status !== '出勤中') {
            return redirect()->back()->with('error', '休憩開始できません。')->throwResponse();
        }

        return [
            'status' => '休憩中',
            'break_start' => now(),
        ];
    }
}