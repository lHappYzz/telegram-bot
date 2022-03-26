<?php

namespace Boot\Log;

use Boot\Src\singleton;
use Exception;

class Logger extends singleton
{
    public const LEVEL_INFO = 0;
    public const LEVEL_ERROR = 1;

    private array $dirNames = [
        0 => 'info',
        1 => 'error',
    ];
    private string $logDirPath = 'storage/logs/';

    public static function logException(Exception $exception, int $level = self::LEVEL_INFO): void
    {
        self::getInstance()->log($level, $exception->getMessage(), $exception->getTrace());
    }

    public static function logInfo(string $message): void
    {
        self::getInstance()->log(self::LEVEL_INFO, $message, []);
    }

    public static function logError(string $message): void
    {
        self::getInstance()->log(self::LEVEL_ERROR, $message, []);
    }

    /**
     * Log to file
     * @param int $level
     * @param string $message
     * @param array $context
     */
    private function log(int $level, string $message, array $context): void
    {
        file_put_contents($this->makeDir($level),
            '['. date('H:i:s') . "] -> ". $this->dirNames[$level] . '. ' . $message . PHP_EOL .

            print_r(array_map(static function ($element) {
                return $element['file'] . '(' . $element['line'] . ')' . ': ' . $element['class'] . $element['type'] . $element['function'] . '()';
            }, $context), true) . PHP_EOL,

            FILE_APPEND
        );
    }

    /**
     * Return logs dir path
     * @param int $level
     * @return string
     */
    private function logDirPath(int $level): string
    {
        return $this->logDirPath . $this->dirNames[$level] . '/';
    }

    /**
     * Return logs file name
     * @return string
     */
    private function logFileName(): string
    {
        return date('Y-m-d') . '.txt';
    }

    /**
     * Compose logs dir with logs filename
     * @param int $level
     * @return string
     */
    private function pathToLogFile(int $level): string
    {
        return $this->logDirPath($level) . $this->logFileName();
    }

    /**
     * Create dir for logs if not exists
     * @param int $level
     * @return string
     */
    private function makeDir(int $level): string
    {
        $pathToLogFile = $this->pathToLogFile($level);

        if (!file_exists($pathToLogFile) &&
            !mkdir($concurrentDirectory = $this->logDirPath($level), 0777, true) &&
            !is_dir($concurrentDirectory))
        {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        if (isset($concurrentDirectory)) {
            $pathToLogFile = $concurrentDirectory . $this->logFileName();
        }

        return $pathToLogFile;
    }
}