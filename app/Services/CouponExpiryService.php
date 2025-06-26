<?php

// app/Services/CouponExpiryService.php

namespace App\Services;

use Carbon\Carbon;

class CouponExpiryService
{
    public function getExpiryData(string $expiryDate, int $couponStatus = 0): array
    {
        // Handle coupon status first (priority logic)
        if ($couponStatus == 2) {
            return [
                'formattedDate' => Carbon::parse($expiryDate)->format('Y-m-d'),
                'isExpired' => false,
                'daysLeft' => 0,
                'statusClass' => 'badge-secondary',
                'statusIcon' => 'fas fa-trash',
                'statusText' => 'محذوف'
            ];
        }

        if ($couponStatus == 1) {
            return [
                'formattedDate' => Carbon::parse($expiryDate)->format('Y-m-d'),
                'isExpired' => false,
                'daysLeft' => 0,
                'statusClass' => 'badge-danger',
                'statusIcon' => 'fas fa-ban',
                'statusText' => 'غير نشط'
            ];
        }

        // If couponStatus == 0, continue with original expiry logic
        $expiryCarbon = Carbon::parse($expiryDate);
        $isExpired = $expiryCarbon->isPast();
        $daysLeft = $expiryCarbon->diffInDays(now(), false);

        $data = [
            'formattedDate' => $expiryCarbon->format('Y-m-d'),
            'isExpired' => $isExpired,
            'daysLeft' => abs($daysLeft),
            'statusClass' => 'badge-success',
            'statusIcon' => 'fas fa-check-circle',
            'statusText' => 'نشط'
        ];

        if ($isExpired) {
            $data['statusClass'] = 'badge-danger';
            $data['statusIcon'] = 'fas fa-times-circle';
            $data['statusText'] = 'منتهي الصلاحية';
        } elseif ($data['daysLeft'] <= 3) {
            $data['statusClass'] = 'badge-warning';
            $data['statusIcon'] = 'fas fa-exclamation-triangle';
            $data['statusText'] = 'ينتهي قريباً';
        } elseif ($data['daysLeft'] <= 7) {
            $data['statusClass'] = 'badge-info';
            $data['statusIcon'] = 'fas fa-clock';
            $data['statusText'] = 'نشط';
        }

        return $data;
    }
}
