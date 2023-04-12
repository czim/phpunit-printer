<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Output;

class NameUtil
{
    public static function friendly(string $method): string
    {
        $suffix = '';

        if (preg_match('#with data set "(?<dataset>.*)"$#', $method, $matches)) {
            $suffix = " [{$matches['dataset']}]";
            $method = substr($method, 0, strpos($method, 'with data set'));
        }

        return static::wordsWithSpaces(
            str_replace('_', ' ', $method)
        ) . $suffix;
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    protected static function wordsWithSpaces(string $value, string $delimiter = ' '): string
    {
        $value = preg_replace('/\s+/u', '', ucwords($value));

        return mb_strtolower(
            preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value),
            'UTF-8'
        );
    }
}
