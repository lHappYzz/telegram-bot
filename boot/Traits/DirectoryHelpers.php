<?php

namespace Boot\Traits;

use Boot\Log\Logger;
use Boot\Src\Singleton;
use ReflectionClass;
use ReflectionException;

trait DirectoryHelpers
{
    private function getClassInstance(string $className): mixed
    {
        try {
            $reflection = (new ReflectionClass($className));
            if (!$reflection->isAbstract() && $reflection->isSubclassOf(Singleton::class)) {
                /** @var Singleton $fullPath */
                $fullPath = $reflection->getName();

                return $fullPath::getInstance();
            }
        } catch (ReflectionException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            die;
        }
        return null;
    }

    private function getCommandsInTheCommandDir(): array
    {
        return $this->getClasses('app'.DIRECTORY_SEPARATOR.'Commands');
    }

    private function getCallbackHandlersInTheHandlersDir(): array
    {
        return $this->getClasses('app'.DIRECTORY_SEPARATOR.'CallbackQueryHandlers');
    }

    private function getClasses(string $dir): array
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