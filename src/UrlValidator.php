<?php

namespace Pkscraper;

class UrlValidator
{
    public static function isUrlCorrect(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}