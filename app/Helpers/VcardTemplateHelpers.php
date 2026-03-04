<?php

/**
 * Shared helper functions for vCard Blade templates.
 * All functions are guarded with function_exists to prevent redeclaration.
 *
 * NOTE: e() and data_get() are already Laravel globals — do NOT redefine them here.
 */

// Alias helpers used by bookshop/coaching GROUP-C templates
if (!function_exists('v')) {
    function v(array $data, string $path, mixed $default = ''): mixed
    {
        $segments = explode('.', $path);
        $current = $data;
        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return $default;
            }
            $current = $current[$segment];
        }
        return $current;
    }
}

if (!function_exists('a')) {
    function a(array $data, string $path): array
    {
        $value = v($data, $path, []);
        return is_array($value) ? $value : [];
    }
}

if (!function_exists('vcard_js_str')) {
    /**
     * JSON-encode a value for safe inline JS embedding.
     * Uses JSON_HEX_TAG | JSON_HEX_AMP to prevent XSS via </script> injection.
     */
    function vcard_js_str(mixed $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    }
}

if (!function_exists('vcard_section_enabled')) {
    /**
     * Check whether a named section is enabled in the vCard data.
     * Looks at $data['_sections_config'][$section] — defaults to true if not set.
     */
    function vcard_section_enabled(array $data, string $section): bool
    {
        return (bool) ($data['_sections_config'][$section]['enabled'] ?? true);
    }
}

if (!function_exists('vcard_format_inr')) {
    /**
     * Format a number as Indian Rupees (₹).
     * e.g. 1234.5 → "₹1,234.50"
     */
    function vcard_format_inr(mixed $amount): string
    {
        $num = (float) $amount;
        $formatted = number_format($num, 2);
        return '₹' . $formatted;
    }
}

if (!function_exists('vcard_text_with_breaks')) {
    /**
     * Escape HTML and convert newlines to <br> tags.
     */
    function vcard_text_with_breaks(mixed $text): string
    {
        return nl2br(htmlspecialchars((string) ($text ?? ''), ENT_QUOTES, 'UTF-8'));
    }
}

if (!function_exists('vcard_star_markup')) {
    /**
     * Generate SVG star rating markup.
     * Used by restaurant-cafe template.
     *
     * @param  int  $filled  Number of filled stars (out of 5)
     */
    function vcard_star_markup(int $filled): string
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $fill  = $i <= $filled ? '#f59e0b' : '#d1d5db';
            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="' . $fill . '"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>';
        }
        return $html;
    }
}

if (!function_exists('js_str')) {
    /**
     * JSON-encode a value for safe inline JavaScript output.
     * Used by minimart, mens-salon, restaurant-cafe, jewelry-shop, electronics-shop templates.
     */
    function js_str(mixed $value): string
    {
        return json_encode($value ?? '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
}

if (!function_exists('format_inr')) {
    /**
     * Format a number in Indian Rupee style (e.g. 1,00,000).
     * Used by jewelry-shop, electronics-shop templates.
     */
    function format_inr(mixed $value): string
    {
        $number = (string) (int) ($value ?? 0);
        $length = strlen($number);

        if ($length <= 3) {
            return $number;
        }

        $lastThree = substr($number, -3);
        $rest      = substr($number, 0, -3);
        $rest      = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);

        return $rest . ',' . $lastThree;
    }
}
