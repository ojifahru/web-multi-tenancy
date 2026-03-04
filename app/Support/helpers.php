<?php

if (! function_exists('localized_route')) {
    /**
     * Generate a localized route URL
     *
     * @param  array|string  $parameters
     */
    function localized_route(string $routeName, $parameters = [], ?string $locale = null): string
    {
        // Ensure $parameters is always an array
        if (! is_array($parameters)) {
            $parameters = [];
        }

        return \App\Support\LocalizationHelper::localizedRoute($routeName, $parameters, $locale);
    }
}

if (! function_exists('get_available_locales')) {
    /**
     * Get all available locales
     */
    function get_available_locales(): array
    {
        return \App\Support\LocalizationHelper::getAvailableLocales();
    }
}

if (! function_exists('get_locale_name')) {
    /**
     * Get locale name
     */
    function get_locale_name(string $locale): string
    {
        return \App\Support\LocalizationHelper::getLocaleName($locale);
    }
}

if (! function_exists('locale_url')) {
    /**
     * Get current page URL in different locale
     */
    function locale_url(string $locale): string
    {
        $currentRoute = request()->route()->getName();
        $parameters = request()->route()->parameters();

        return localized_route($currentRoute, $parameters, $locale);
    }
}
