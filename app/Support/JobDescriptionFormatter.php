<?php

namespace App\Support;

use Illuminate\Support\Str;

class JobDescriptionFormatter
{
    public static function toHtml(?string $description): string
    {
        $description = trim((string) $description);

        if ($description === '') {
            return '';
        }

        $decoded = html_entity_decode($description, ENT_QUOTES | ENT_HTML5);

        if (! self::containsHtml($decoded)) {
            return Str::markdown($decoded)->toHtml();
        }

        return self::sanitizeHtml($decoded);
    }

    private static function containsHtml(string $value): bool
    {
        return (bool) preg_match('/<[a-z][\s\S]*>/i', $value);
    }

    private static function sanitizeHtml(string $html): string
    {
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html) ?? '';
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html) ?? '';
        $html = strip_tags($html, '<p><br><ul><ol><li><strong><b><em><i><h2><h3><h4><a>');

        $html = preg_replace_callback('/<a\b([^>]*)>/i', function (array $match): string {
            if (! preg_match('/href=["\']([^"\']+)["\']/i', $match[1], $hrefMatch)) {
                return '<a>';
            }

            $href = html_entity_decode($hrefMatch[1], ENT_QUOTES | ENT_HTML5);
            if (! filter_var($href, FILTER_VALIDATE_URL) || ! str_starts_with($href, 'http')) {
                return '<a>';
            }

            return '<a href="' . e($href) . '" target="_blank" rel="nofollow noopener noreferrer">';
        }, $html) ?? '';

        $html = preg_replace('/<(\/?)(p|br|ul|ol|li|strong|b|em|i|h2|h3|h4)\b[^>]*>/i', '<$1$2>', $html) ?? '';

        return trim($html);
    }
}
