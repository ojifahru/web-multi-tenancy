<?php

namespace App\Support;

use Illuminate\Support\Facades\App;

class LocalizationHelper
{
    /**
     * Generate a localized route URL
     *
     * @param  array|string  $parameters
     */
    public static function localizedRoute(string $routeName, $parameters = [], ?string $locale = null): string
    {
        // Ensure parameters is an array
        if (! is_array($parameters)) {
            $parameters = [];
        }

        $locale = $locale ?? App::getLocale();

        // Validate locale
        if (! in_array($locale, ['en', 'id'])) {
            $locale = (string) config('app.locale', 'id');
        }

        if (str_ends_with($routeName, '.id')) {
            $routeName = substr($routeName, 0, -3);
        }

        // Map route names to their localized versions
        $routeMap = [
            'public.home' => 'public.home',
            'public.news.index' => $locale === 'id' ? 'public.news.index.id' : 'public.news.index',
            'public.news.show' => $locale === 'id' ? 'public.news.show.id' : 'public.news.show',
            'public.lecturers.index' => $locale === 'id' ? 'public.lecturers.index.id' : 'public.lecturers.index',
            'public.lecturers.show' => $locale === 'id' ? 'public.lecturers.show.id' : 'public.lecturers.show',
            'public.facilities.index' => $locale === 'id' ? 'public.facilities.index.id' : 'public.facilities.index',
            'public.facilities.show' => $locale === 'id' ? 'public.facilities.show.id' : 'public.facilities.show',
            'public.about' => $locale === 'id' ? 'public.about.id' : 'public.about',
            'public.contact' => $locale === 'id' ? 'public.contact.id' : 'public.contact',
            'public.contact.store' => $locale === 'id' ? 'public.contact.store.id' : 'public.contact.store',
        ];

        $actualRouteName = $routeMap[$routeName] ?? $routeName;

        // Ensure parameters array has locale key, overriding existing value
        $parameters = array_merge($parameters, ['locale' => $locale]);

        return route($actualRouteName, $parameters);
    }

    /**
     * Get all available locales
     */
    public static function getAvailableLocales(): array
    {
        return ['en', 'id'];
    }

    /**
     * Get locale name
     */
    public static function getLocaleName(string $locale): string
    {
        $names = [
            'en' => 'English',
            'id' => 'Bahasa Indonesia',
        ];

        return $names[$locale] ?? $locale;
    }
}
