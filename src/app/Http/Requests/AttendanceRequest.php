<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'clock_in' => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i', 'after:clock_in'],
            'remarks' => ['required', 'string'],
            'breaks' => 'array',
            'breaks.*.break_start' => ['nullable', 'date_format:H:i'],
            'breaks.*.break_end' => ['nullable', 'date_format:H:i', 'after:breaks.*.break_start'],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を記入してください',
            'clock_out.required' => '退勤時間を記入してください',
            'remarks.required' => '備考を記入してください',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $clockIn = Carbon::createFromFormat('H:i', $this->clock_in);
            $clockOut = Carbon::createFromFormat('H:i', $this->clock_out);

            if ($clockIn->greaterThanOrEqualTo($clockOut)) {
                $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
            }

            if ($this->break_start && $this->break_end) {
                $breakStart = Carbon::createFromFormat('H:i', $this->break_start);
                $breakEnd = Carbon::createFromFormat('H:i', $this->break_end);

                if ($breakStart->lessThan($clockIn) || $breakEnd->greaterThan($clockOut)) {
                    $validator->errors()->add('breaks.*.break_start', '休憩時間が勤務時間外です');
                }
            }
        });
    }
}
