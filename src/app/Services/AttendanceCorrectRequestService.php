<?php

namespace App\Services;

use App\Models\AttendanceCorrectRequest;

class AttendanceCorrectRequestService
{
    public function getCorrectionRequestsByTab($userId, $tab)
    {
        $query = AttendanceCorrectRequest::byUser($userId);

        if ($tab === 'pending') {
            $query = $query->byStatus(AttendanceCorrectRequest::STATUS_PENDING);
        } elseif ($tab === 'approved') {
            $query = $query->byStatus(AttendanceCorrectRequest::STATUS_APPROVED);
        }

        return $query->get();
    }
}