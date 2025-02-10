<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    public function handleEndBreak()
    {
        app(\App\Services\AttendanceService::class)->endBreak();
    }
}
