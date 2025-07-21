<?php

namespace App\Services;

class CarMakeNameService
{
    public function getNameData($rawName)
    {
        $name = 'غير متوفر';
        $make_name_en = 'غير متوفر';

        if (is_string($rawName)) {
            $decoded_name = json_decode($rawName, true);

            if (is_array($decoded_name)) {
                $name = $decoded_name['ar'] ?? 'غير متوفر';
                $make_name_en = $decoded_name['en'] ?? 'غير متوفر';
            } else {
                $name = $rawName;
            }
        }

        return [
            'ar' => $name,
            'en' => $make_name_en
        ];
    }
}