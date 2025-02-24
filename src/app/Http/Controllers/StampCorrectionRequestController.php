<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class StampCorrectionRequestController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        if (!in_array($tab, ['pending', 'approved'])) {
            $tab = 'pending';
        }

        $userId = Auth::id();

        $attendances = Attendance::byUser($userId);

        if ($tab === 'pending') {
            $attendances = $attendances->where('approval_status', Attendance::APPROVAL_PENDING)->get();
        } elseif ($tab === 'approved') {
            $attendances = $attendances->where('approval_status', Attendance::APPROVAL_APPROVED)->get();
        }

        return view('stamp_correction_request.index', [
            'attendances' => $attendances,
            'tab' => $tab,
        ]);
    }
}
