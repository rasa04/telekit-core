<?php

namespace Core\Storage;

use Core\Env;

class Storage
{
    use Env;

    private static string $path = __DIR__ . "/../../storage/";

    private static function getFileLink($file): string
    {
        return sprintf(self::storage_path() ?? self::$path, $file);
    }

    public static function get(string $file, $associative = null): array
    {
        return json_decode(file_get_contents(self::getFileLink($file)), $associative) ?? [];
    }

    public static function save(array $data, string $file = "data.json", bool $overwrite = false): void
    {
        $content = self::get($file);

        ($overwrite)
            ? $content = $data
            : $content[] = $data;

        file_put_contents(self::getFileLink($file), json_encode($content));
    }
}