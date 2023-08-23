<?php

namespace Boot\Traits;

trait DirectoryHelpers
{
    private function getFiles(string $dir): array
    {
        $classes = scandir($dir);

        if ($classes !== false) {
            foreach ($classes as &$class) {
                if (!is_file($dir.DIRECTORY_SEPARATOR.$class)) {
                    unset($class);
                }
            }
            return array_diff(array_values($classes), ['.', '..']);
        }

        return [];
    }
}