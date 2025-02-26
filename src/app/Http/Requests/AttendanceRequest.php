<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'clock_in' => 'required|date_format:H:i|before:clock_out',
            'clock_out' => 'required|date_format:H:i|after:clock_in',
            'remarks' => 'required|string',
            'breaks' => 'array|nullable',
            'breaks.*.break_start' => 'nullable|date_format:H:i|before:breaks.*.break_end|after:clock_in',
            'breaks.*.break_end' => 'nullable|date_format:H:i|before:clock_out',
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を記入してください',
            'clock_in.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'clock_out.required' => '退勤時間を記入してください',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'remarks.required' => '備考を記入してください',
            'breaks.*.break_start.before' => '休憩開始時間もしくは休憩終了時間が不適切な値です',
            'breaks.*.break_start.after' => '休憩時間が勤務時間外です。',
            'breaks.*.break_end.before' => '休憩時間が勤務時間外です。',
        ];
    }
}
