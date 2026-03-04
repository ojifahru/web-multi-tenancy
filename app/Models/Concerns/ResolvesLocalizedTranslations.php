<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait ResolvesLocalizedTranslations
{
    public function resolveLocalizedValue(string $attribute, ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();

        $localesToCheck = array_values(array_unique(array_filter([
            is_string($locale) ? $locale : null,
            config('app.fallback_locale'),
            config('app.locale'),
            'id',
            'en',
        ], fn($value): bool => is_string($value) && trim($value) !== '')));

        $translations = $this->supportsTranslatableAttribute($attribute)
            ? $this->getTranslations($attribute)
            : [];

        foreach ($localesToCheck as $localeToCheck) {
            $value = $translations[$localeToCheck] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        foreach ($translations as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        $rawValue = $this->getRawOriginal($attribute);

        if (is_string($rawValue)) {
            $decoded = json_decode($rawValue, true);

            if (is_array($decoded)) {
                foreach ($decoded as $value) {
                    if (is_string($value) && trim($value) !== '') {
                        return trim($value);
                    }
                }
            }

            if (trim($rawValue) !== '') {
                return trim($rawValue);
            }
        }

        return null;
    }

    public function resolveSlug(?string $locale = null): ?string
    {
        if (! $this->supportsTranslatableAttribute('slug')) {
            $rawSlug = $this->getRawOriginal('slug');

            if (is_string($rawSlug) && trim($rawSlug) !== '') {
                return trim($rawSlug);
            }

            $slugAttribute = $this->getAttribute('slug');

            if (is_string($slugAttribute) && trim($slugAttribute) !== '') {
                return trim($slugAttribute);
            }

            return null;
        }

        return $this->resolveLocalizedValue('slug', $locale);
    }

    public function matchesSlug(string $slug): bool
    {
        $normalizedSlug = $this->normalizeSlugValue($slug);

        if ($normalizedSlug === null) {
            return false;
        }

        $translatedSlugs = $this->supportsTranslatableAttribute('slug')
            ? $this->getTranslations('slug')
            : [];

        foreach ($translatedSlugs as $translatedSlug) {
            if ($this->normalizeSlugValue($translatedSlug) === $normalizedSlug) {
                return true;
            }
        }

        $rawSlug = (string) $this->getRawOriginal('slug');

        if (trim($rawSlug) !== '') {
            $decoded = json_decode($rawSlug, true);

            if (is_array($decoded)) {
                foreach ($decoded as $decodedSlug) {
                    if ($this->normalizeSlugValue($decodedSlug) === $normalizedSlug) {
                        return true;
                    }
                }
            }

            if ($this->normalizeSlugValue($rawSlug) === $normalizedSlug) {
                return true;
            }
        }

        return false;
    }

    private function normalizeSlugValue(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $normalized = trim(urldecode($value));

        if ($normalized === '') {
            return null;
        }

        return Str::lower($normalized);
    }

    private function supportsTranslatableAttribute(string $attribute): bool
    {
        if (! method_exists($this, 'getTranslatableAttributes')) {
            return false;
        }

        return in_array($attribute, $this->getTranslatableAttributes(), true);
    }
}
