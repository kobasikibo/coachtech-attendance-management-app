<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    public function handleStartBreak()
    {
        app(\App\Services\AttendanceService::class)->startBreak();
    }
}
