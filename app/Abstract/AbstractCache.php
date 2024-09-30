<?php
declare(strict_types=1);

namespace App\Abstract;

use Hyperf\Redis\Redis;
use RedisException;

/**
 * @AbstractCache
 * @\App\Abstract\AbstractCache
 */
abstract class AbstractCache
{

    /**
     * @var Redis $redis
     */
    protected Redis $redis;

    abstract public function __construct(Redis $redis);

    /**
     * key of cache
     * @var string $cacheKey
     */
    protected string $cacheKey;

    /**
     * @param string|int $key
     * @return mixed
     * @throws RedisException
     */
    public function get(string|int $key): mixed
    {

        return $this->redis->get($this->cacheKey . '_' . $key);
    }

    /**
     * @param string|int $key
     * @param mixed $value
     * @param array{
     *     EX: int,
     *     PX: int,
     *     EXAT: int,
     *     PXAT: int,
     *     NX: int,
     *     XX: int,
     *     GET: string,
     * }|int|null $options
     * @return void
     * @throws RedisException
     */
    public function set(string|int $key, mixed $value, mixed $options = null): void
    {
        $this->redis->set($this->cacheKey . '_' . $key, $value, $options);
    }

    /**
     * @return mixed
     * @throws RedisException
     */
    public function getKey(): mixed
    {
        return $this->redis->get($this->cacheKey);
    }

    /**
     * @param mixed $value
     * @param array{
     *     EX: int,
     *     PX: int,
     *     EXAT: int,
     *     PXAT: int,
     *     NX: int,
     *     XX: int,
     *     GET: string,
     * }|int|null $options
     * @return void
     * @throws RedisException
     */
    public function setKey(mixed $value, mixed $options = null): void
    {
        $this->redis->set($this->cacheKey, $value, $options);
    }

    /**
     * @param int $expireTime
     * @return void
     * @throws RedisException
     */
    public function lock(int $expireTime): void
    {
        $this->redis->set($this->cacheKey . '_lock', null, [
            'nx',
            'ex' => $expireTime,
        ]);
    }

    /**
     * @return bool
     * @throws RedisException
     */
    public function existLock(): bool
    {
        return (boolean)$this->redis->exists($this->cacheKey . '_lock');
    }

    /**
     * @return void
     * @throws RedisException
     */
    public function unlock(): void
    {
        $this->redis->del($this->cacheKey . '_lock');
    }
}