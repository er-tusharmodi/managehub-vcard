<?php

namespace App\Helpers;

use Illuminate\Support\Str;

/**
 * Generate username from subdomain by taking first letter of each hyphen-separated word
 * and appending 5 random digits
 * Example: 'john-doe-smith' => 'jds12345'
 */
class UsernameGenerator
{
    public static function generateFromSubdomain(string $subdomain): string
    {
        $words = explode('-', strtolower($subdomain));
        $username = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $username .= $word[0];
            }
        }
        
        // Append 5 random digits
        $randomDigits = rand(10000, 99999);
        return $username . $randomDigits;
    }
}
