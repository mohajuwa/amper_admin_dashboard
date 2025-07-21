<?php

namespace App\Services;

class PopularityBadgeService
{
    public function getBadgeData($popularity)
    {
        $popularity = $popularity ?? 0;
        $badgeClass = '';
        $label = '';
        $icon = '';

        if ($popularity >= 90) {
            $badgeClass = 'badge-gradient-gold';
            $label = 'الاعلى شهرة';
            $icon = '🏆';
        } elseif ($popularity >= 80) {
            $badgeClass = 'badge-gradient-purple';
            $label = 'شهيرة جداً';
            $icon = '🥇';
        } elseif ($popularity >= 70) {
            $badgeClass = 'badge-gradient-blue';
            $label = 'شهرة جيدة';
            $icon = '🥈';
        } elseif ($popularity >= 60) {
            $badgeClass = 'badge-gradient-green';
            $label = 'جيد';
            $icon = '🥉';
        } elseif ($popularity >= 40) {
            $badgeClass = 'badge-gradient-orange';
            $label = 'مقبول';
            $icon = '📊';
        } elseif ($popularity > 0) {
            $badgeClass = 'badge-gradient-red';
            $label = 'ضعيف';
            $icon = '📉';
        } else {
            $badgeClass = 'badge-secondary';
            $label = 'غير محدد';
            $icon = '❓';
        }

        return [
            'popularity' => $popularity,
            'badgeClass' => $badgeClass,
            'label' => $label,
            'icon' => $icon
        ];
    }
}