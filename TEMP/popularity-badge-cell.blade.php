
{{-- `resources/views/admin/partials/components/tables/popularity-badge-cell.blade.php`: --}}

@php
    $popularity = $record->getAttributes()['popularity'] ?? 0;
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
        $label}