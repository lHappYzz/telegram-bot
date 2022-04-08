<?php

namespace Boot\Traits;

use Boot\Src\CallbackQueryHandler;

trait helpers {

    private function getCallbackQueryHandlerClassInstance(string $className): CallbackQueryHandler
    {
        return $this->getClassInstance(
            '\\App\\CallbackQueryHandlers\\',
            $className,
            $this->getCallbackHandlersInTheHandlersDir()
        );
    }

    private function resolveCallbackQueryHandlerName(string $callbackData): string
    {
        return ucfirst(strtolower(preg_replace('/[^A-Za-z0-9]/', '', $callbackData))) . 'Handler';
    }

    private function getCommandClassInstance($className) {
        return $this->getClassInstance('\\App\\Commands\\', $className, $this->getCommandsInTheCommandDir());
    }

    private function getCommandsInTheCommandDir()
    {
        return $this->getClasses('app'.DIRECTORY_SEPARATOR.'Commands');
    }

    private function getCallbackHandlersInTheHandlersDir()
    {
        return $this->getClasses('app'.DIRECTORY_SEPARATOR.'CallbackQueryHandlers');
    }

    /**
     * Convert string camel case style to snake case
     * @param string $input
     * @return string
     */
    private function camelCaseToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * Convert string snake case style to camel case
     * @param string $input
     * @return string
     */
    private function snakeCaseToCamelCase(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }

    private function getClassInstance(string $namespace, string $className, array $classes)
    {
        foreach ($classes as $class) {
            if ($class === $className.'.php' && $class !== 'baseCommand.php') {
                $fullPath = $namespace.$className;
                return $fullPath::getInstance();
            }
        }
        return null;
    }

    private function getClasses(string $dir)
    {
        $classes = scandir($dir);
        foreach ($classes as &$class) {
            if (!is_file($dir.DIRECTORY_SEPARATOR.$class)) {
                unset($class);
            }
        }
        return array_values($classes);
    }
}