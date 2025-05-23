<?php

namespace App\Helpers;

class IconsSelectHelper
{
    private static array $icons = [];

    public static function getIconOptions(): array
    {
        if (!empty(self::$icons)) {
            return self::$icons;
        }

        $path = public_path('data/icons.json');

        if (!file_exists($path)) {
            return [];
        }

        $icons = json_decode(file_get_contents($path), true);

        if (!is_array($icons)) {
            return [];
        }

        return self::$icons = array_combine($icons, $icons);
    }
}