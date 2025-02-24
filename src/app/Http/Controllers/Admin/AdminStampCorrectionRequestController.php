<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AdminStampCorrectionRequestController
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        if (!in_array($tab, ['pending', 'approved'])) {
            $tab = 'pending';
        }

        if ($tab === 'pending') {
            $attendances = Attendance::where('approval_status', Attendance::APPROVAL_PENDING)->get();
        } elseif ($tab === 'approved') {
            $attendances = Attendance::where('approval_status', Attendance::APPROVAL_APPROVED)->get();
        }

        return view('admin.stamp_correction_request-index', [
            'attendances' => $attendances,
            'tab' => $tab,
        ]);
    }
}
