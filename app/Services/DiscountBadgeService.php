<?php
// app/Services/DiscountBadgeService.php

namespace App\Services;

class DiscountBadgeService
{
    public function getBadgeData(float $discount): array
    {
        $badgeClass = 'badge-gradient-orange';
        $icon = '🎯';
        
        if ($discount >= 50) {
            $badgeClass = 'badge-gradient-gold';
            $icon = '🔥';
        } elseif ($discount >= 30) {
            $badgeClass = 'badge-gradient-purple';
            $icon = '⭐';
        } elseif ($discount >= 20) {
            $badgeClass = 'badge-gradient-blue';
            $icon = '💎';
        } elseif ($discount >= 10) {
            $badgeClass = 'badge-gradient-green';
            $icon = '✨';
        }
        
        return [
            'discount' => $discount,
            'badgeClass' => $badgeClass,
            'icon' => $icon
        ];
    }
}