<?php

if (!function_exists('getTagType')) {
    /**
     * Get the tag type
     *
     * @param string|null $mode
     *
     * @return array
     */
    function getTagType(string $mode = null): array
    {
        $tagTypes = collect(app('tagType'));

        if ($mode === 'key') {
            return $tagTypes->keys()->toArray();
        }

        if ($mode === 'value') {
            return $tagTypes->values()->map(function ($value) {
                return trans($value);
            })->toArray();
        }

        return $tagTypes->map(function ($value) {
            return trans($value);
        })->toArray();
    }
}
