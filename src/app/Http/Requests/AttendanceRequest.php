<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'clock_in' => 'required|date_format:H:i|before:clock_out',
            'clock_out' => 'required|date_format:H:i|after:clock_in',
            'remarks' => 'required|string',
            'breaks' => 'array|nullable',
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
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $clockIn = Carbon::createFromFormat('H:i', $this->clock_in);
            $clockOut = Carbon::createFromFormat('H:i', $this->clock_out);

            if ($this->has('breaks') && is_array($this->breaks)) {
                foreach ($this->breaks as $key => $break) {
                    // break_start と break_end のどちらかが空ならスキップ
                    if (empty($break['break_start']) || empty($break['break_end'])) {
                        continue;
                    }

                    $breakStart = Carbon::createFromFormat('H:i', $break['break_start']);
                    $breakEnd = Carbon::createFromFormat('H:i', $break['break_end']);

                    // 休憩開始が休憩終了より後の場合
                    if ($breakStart->greaterThanOrEqualTo($breakEnd)) {
                        $validator->errors()->add("breaks.$key.break_start", '休憩開始時間もしくは休憩終了時間が不適切な値です');
                    }

                    // 休憩時間が勤務時間外の場合
                    if ($breakStart->lessThan($clockIn) || $breakEnd->greaterThan($clockOut)) {
                        $validator->errors()->add("breaks.$key.break_start", '休憩時間が勤務時間外です');
                    }
                }
            }
        });
    }
}
