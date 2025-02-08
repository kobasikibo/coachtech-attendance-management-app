<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attendance;

class EndBreakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [];
    }

    public function validateEndBreak()
    {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->first();

        if (!$attendance || $attendance->status !== '休憩中') {
            return redirect()->back()->with('error', '休憩終了できません。')->throwResponse();
        }

        return [
            'status' => '出勤中',
            'break_end' => now(),
        ];
    }
}