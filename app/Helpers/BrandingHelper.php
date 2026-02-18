<?php

namespace App\Helpers;

use App\Models\WebsitePage;

class BrandingHelper
{
    protected static $branding = null;

    /**
     * Get branding data from database
     */
    public static function getBranding()
    {
        if (static::$branding === null) {
            $page = WebsitePage::where('slug', 'home')->first();
            static::$branding = $page?->data['branding'] ?? [];
        }
        return static::$branding;
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl()
    {
        $branding = static::getBranding();
        return $branding['logo_url'] ?? asset('backendtheme/assets/images/logo-light.png');
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl()
    {
        $branding = static::getBranding();
        return $branding['favicon_url'] ?? asset('backendtheme/assets/images/favicon.ico');
    }

    /**
     * Get footer logo URL
     */
    public static function getFooterLogoUrl()
    {
        $branding = static::getBranding();
        return $branding['footer_logo_url'] ?? asset('backendtheme/assets/images/logo-light.png');
    }

    /**
     * Get primary color
     */
    public static function getPrimaryColor()
    {
        $branding = static::getBranding();
        return $branding['primary_color'] ?? '#000000';
    }

    /**
     * Get secondary color
     */
    public static function getSecondaryColor()
    {
        $branding = static::getBranding();
        return $branding['secondary_color'] ?? '#666666';
    }
}
