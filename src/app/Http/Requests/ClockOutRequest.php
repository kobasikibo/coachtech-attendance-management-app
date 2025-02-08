<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attendance;

class ClockOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [];
    }

    public function validateClockOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->first();

        if (!$attendance || $attendance->status === '退勤済') {
            return redirect()->back()->with('error', '退勤できません。')->throwResponse();
        }

        return [
            'status' => '退勤済',
            'clock_out' => now(),
        ];
    }
}