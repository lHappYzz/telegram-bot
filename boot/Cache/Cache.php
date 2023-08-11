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
use ReflectionException;

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
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->psr16Adapter->get($key, $default);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function set(string $key, mixed $value, int $ttl): bool
    {
        return $this->psr16Adapter->set($key, $value, $ttl);
    }

    /**
     * @param string $key
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function delete(string $key): bool
    {
        return $this->psr16Adapter->delete($key);
    }

    /**
     * @param string $key
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function has(string $key): bool
    {
        return $this->psr16Adapter->has($key);
    }


}