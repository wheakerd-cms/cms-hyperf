<?php
declare(strict_types=1);

namespace App\Traits\Cache;

use BadMethodCallException;
use Hyperf\Redis\Redis;
use Psr\Log\LoggerInterface;
use RedisException;

/**
 * @TraitCacheCall
 * @\App\Traits\Cache\TraitCacheCall
 * @property Redis $redis
 * @uses \Redis
 */
trait TraitCacheCall
{

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * lock
     * @param string $lockKey
     * @param int $lockTimeout
     * @return bool
     */
    public function acquireLock(string $lockKey, int $lockTimeout = 3): bool
    {
        try {
            $isLocked = $this->redis->set($this->redis->poolName . '_locked_' . $lockKey, 'locked', [
                'NX',
                'EX' => $lockTimeout,
            ]);
        } catch (RedisException) {
            return false;
        }

        return $isLocked;
    }

    /**
     * unlock
     * @param string $lockKey
     * @return void
     */
    public function releaseLock(string $lockKey): void
    {
        try {
            $this->redis->del($this->redis->poolName . '_locked_' . $lockKey);
        } catch (RedisException) {
        }
    }

    public function exceptionHandler(string $class, string $method, string $message): void
    {
        $this->logger->warning('RedisException', ['message' => "$class::$method $message"]);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws RedisException
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (!method_exists(\Redis::class, $name)) {
            throw new BadMethodCallException('Call to undefined method Redis::' . $name . '()');
        }

        return $this->redis->{$name}(... $arguments);
    }
}