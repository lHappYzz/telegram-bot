<?php

namespace Boot\Traits;

use Boot\Src\CallbackQueryHandler;
use Boot\Src\ReplyMarkup\InlineKeyboardButton;

trait Helpers
{
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
        return  $this->arrayFirst(explode(InlineKeyboardButton::CALLBACK_DATA_DELIMITER, $callbackData)) .
            CallbackQueryHandler::CALLBACK_QUERY_HANDLERS_ENDING;
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
            if ($class === $className.'.php' && $class !== 'BaseCommand.php') {
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

    private function arrayFirst(array $array)
    {
        if (($firstElement = reset($array)) === false) {
            return '';
        }
        return $firstElement;
    }

    private function arrayLast(array $array)
    {
        if (($lastElement = end($array)) === false) {
            return '';
        }
        return $lastElement;
    }
}