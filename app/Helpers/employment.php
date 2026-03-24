<?php

if (! function_exists('employment_label')) {
    function employment_label(mixed $type): string
    {
        $value = is_object($type) && property_exists($type, 'value') ? $type->value : (string) $type;

        return match ($value) {
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Kontrak',
            'freelance' => 'Freelance',
            'internship' => 'Magang',
            default => $value,
        };
    }
}
