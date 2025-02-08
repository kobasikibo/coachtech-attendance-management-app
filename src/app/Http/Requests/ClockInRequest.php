<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attendance;

class ClockInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [];
    }

    public function validateClockIn()
    {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->first();

        if ($attendance) {
            return redirect()->back()->with('error', '本日はすでに出勤しています。')->throwResponse();
        }

        return [
            'user_id' => auth()->id(),
            'status' => '出勤中',
            'clock_in' => now(),
        ];
    }
}