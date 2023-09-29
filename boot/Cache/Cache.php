<?php

namespace Boot\Cache;

use Boot\Src\Abstracts\Singleton;
use Phpfastcache\Exceptions\PhpfastcacheDriverCheckException;
use Phpfastcache\Exceptions\PhpfastcacheDriverException;
use Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use Phpfastcache\Exceptions\PhpfastcacheLogicException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Phpfastcache\Helper\Psr16Adapter;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;
use RuntimeException;

class Cache extends Singleton
{
    /** @var string */
    private string $defaultDriver = 'files';

    /** @var Psr16Adapter */
    private Psr16Adapter $psr16Adapter;

    /**
     * @throws ReflectionException
     * @throws PhpfastcacheDriverCheckException
     * @throws PhpfastcacheDriverException
     * @throws PhpfastcacheDriverNotFoundException
     * @throws PhpfastcacheInvalidArgumentException
     * @throws PhpfastcacheInvalidConfigurationException
     * @throws PhpfastcacheLogicException
     */
    public function __construct()
    {
        parent::__construct();

        $this->psr16Adapter = new Psr16Adapter($this->defaultDriver);
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        try {
            return $this->psr16Adapter->get($key, $default);
        } catch (InvalidArgumentException|PhpfastcacheSimpleCacheException $e) {
            throw new RuntimeException($e);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set(string $key, mixed $value, int $ttl): bool
    {
        try {
            return $this->psr16Adapter->set($key, $value, $ttl);
        } catch (InvalidArgumentException|PhpfastcacheSimpleCacheException $e) {
            throw new RuntimeException($e);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        try {
            return $this->psr16Adapter->delete($key);
        } catch (PhpfastcacheSimpleCacheException $e) {
            throw new RuntimeException($e);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        try {
            return $this->psr16Adapter->has($key);
        } catch (PhpfastcacheSimpleCacheException $e) {
            throw new RuntimeException($e);
        }
    }


}