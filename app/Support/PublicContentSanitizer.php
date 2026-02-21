<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use Illuminate\Support\Str;

class PublicContentSanitizer
{
    public function sanitizeHtml(?string $value, ?string $fallback = null): string
    {
        $normalizedValue = trim((string) $value);

        if ($normalizedValue === '') {
            return is_null($fallback) ? '' : e($fallback);
        }

        $withoutBlockedTags = preg_replace(
            '/<\s*(script|style|iframe|object|embed|form|input|button|textarea|select|meta|link)[^>]*>.*?<\s*\/\s*\1\s*>/is',
            '',
            $normalizedValue,
        );
        $withoutBlockedTags = preg_replace(
            '/<\s*(script|style|iframe|object|embed|form|input|button|textarea|select|meta|link)\b[^>]*\/?>/is',
            '',
            (string) $withoutBlockedTags,
        );

        $allowedTags = '<a><blockquote><br><code><em><figcaption><figure><h1><h2><h3><h4><h5><h6><hr><img><li><ol><p><pre><strong><u><ul>';
        $sanitizedValue = strip_tags((string) $withoutBlockedTags, $allowedTags);

        return $this->cleanAttributes($sanitizedValue);
    }

    protected function cleanAttributes(string $html): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previousErrors = libxml_use_internal_errors(true);
        $loaded = $document->loadHTML(
            '<?xml encoding="utf-8" ?><div>' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrors);

        if (! $loaded) {
            return $html;
        }

        $wrapper = $document->getElementsByTagName('div')->item(0);

        if (! $wrapper instanceof DOMElement) {
            return $html;
        }

        for ($index = $wrapper->getElementsByTagName('*')->length - 1; $index >= 0; $index--) {
            $node = $wrapper->getElementsByTagName('*')->item($index);

            if (! $node instanceof DOMElement) {
                continue;
            }

            $this->sanitizeAttributes($node);
        }

        $cleanedHtml = '';

        foreach ($wrapper->childNodes as $childNode) {
            $cleanedHtml .= $document->saveHTML($childNode);
        }

        return $cleanedHtml;
    }

    protected function sanitizeAttributes(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);
        $isAnchorTag = $tag === 'a';
        $isImageTag = $tag === 'img';
        $href = $element->getAttribute('href');
        $target = $element->getAttribute('target');
        $rel = $element->getAttribute('rel');
        $source = $element->getAttribute('src');
        $alternativeText = $element->getAttribute('alt');
        $imageWidth = $element->getAttribute('width');
        $imageHeight = $element->getAttribute('height');

        for ($attributeIndex = $element->attributes->length - 1; $attributeIndex >= 0; $attributeIndex--) {
            $attribute = $element->attributes->item($attributeIndex);

            if (is_null($attribute)) {
                continue;
            }

            $attributeName = strtolower($attribute->name);

            if (Str::startsWith($attributeName, 'on') || $attributeName === 'style') {
                $element->removeAttribute($attribute->name);

                continue;
            }

            if (! $isAnchorTag && ! $isImageTag) {
                $element->removeAttribute($attribute->name);
            }
        }

        if (! $isAnchorTag && ! $isImageTag) {
            return;
        }

        if ($isImageTag) {
            if ($source !== '' && $this->isSafeMediaSource($source)) {
                $element->setAttribute('src', $source);
                $element->setAttribute('alt', trim($alternativeText));
                $element->setAttribute('loading', 'lazy');
                $element->setAttribute('decoding', 'async');
            } else {
                $element->parentNode?->removeChild($element);

                return;
            }

            if ($imageWidth !== '' && ctype_digit($imageWidth)) {
                $element->setAttribute('width', $imageWidth);
            } else {
                $element->removeAttribute('width');
            }

            if ($imageHeight !== '' && ctype_digit($imageHeight)) {
                $element->setAttribute('height', $imageHeight);
            } else {
                $element->removeAttribute('height');
            }

            return;
        }

        if ($href !== '' && $this->isSafeHref($href)) {
            $element->setAttribute('href', $href);
        } else {
            $element->removeAttribute('href');
        }

        if ($target === '_blank') {
            $element->setAttribute('target', '_blank');

            $relValues = preg_split('/\s+/', trim($rel)) ?: [];
            $relValues = array_filter($relValues);
            $relValues[] = 'noopener';
            $relValues[] = 'noreferrer';
            $relValues = array_values(array_unique($relValues));

            $element->setAttribute('rel', implode(' ', $relValues));
        } else {
            $element->removeAttribute('target');
            $element->removeAttribute('rel');
        }
    }

    protected function isSafeHref(string $href): bool
    {
        $normalizedHref = trim($href);

        if ($normalizedHref === '') {
            return false;
        }

        if (preg_match('/^(javascript|data|vbscript):/i', $normalizedHref) === 1) {
            return false;
        }

        if (Str::startsWith($normalizedHref, ['#', '/'])) {
            return true;
        }

        if (Str::startsWith($normalizedHref, ['mailto:', 'tel:'])) {
            return true;
        }

        $scheme = strtolower((string) parse_url($normalizedHref, PHP_URL_SCHEME));

        if ($scheme === '') {
            return true;
        }

        return in_array($scheme, ['http', 'https'], true);
    }

    protected function isSafeMediaSource(string $source): bool
    {
        $normalizedSource = trim($source);

        if ($normalizedSource === '') {
            return false;
        }

        if (preg_match('/^(javascript|data|vbscript):/i', $normalizedSource) === 1) {
            return false;
        }

        if (Str::startsWith($normalizedSource, '/')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($normalizedSource, PHP_URL_SCHEME));

        if ($scheme === '') {
            return true;
        }

        return in_array($scheme, ['http', 'https'], true);
    }
}
