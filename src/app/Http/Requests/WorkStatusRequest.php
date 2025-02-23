<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\WorkStatusService;
use Illuminate\Support\Facades\Auth;

abstract class WorkStatusRequest extends FormRequest
{
    protected WorkStatusService $attendanceService;

    public function __construct(WorkStatusService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function authorize(): bool
    {
        return Auth::check();
    }

    abstract public function handle();
}
