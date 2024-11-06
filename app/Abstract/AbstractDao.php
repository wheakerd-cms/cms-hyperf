<?php
/**
 * @noinspection PhpUnused
 * @noinspection DuplicatedCode
 */
declare(strict_types=1);

namespace App\Abstract;

use Carbon\Carbon;
use Closure;
use Hyperf\Database\Model\Builder;
use Hyperf\Paginator\LengthAwarePaginator;
use Hyperf\Stringable\Str;

/**
 * @AbstractDao
 * @\App\Abstract\AbstractDao
 */
abstract class AbstractDao
{

    /**
     * @var AbstractModel $model
     */
    private AbstractModel $model;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @param bool $exists
     * @return AbstractModel
     */
    public function getModel(array $attributes = [], bool $exists = false): AbstractModel
    {
        return $this->model->newInstance($attributes, $exists);
    }

    /**
     * @return Builder
     */
    public function getNewQuery(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options): bool
    {
        $attributes = $this->getModel($options)->getAttributes();

        return $this->getModel()->save($attributes);
    }

    /**
     * @param int|array $where
     * @return bool
     */
    public function delete(int|array $where): bool
    {
        if (is_int($where) || array_is_list($where)) {
            return (boolean)$this->getModel()->destroy($where);
        }
        return $this->getModel()->where($where)->delete();
    }

    /**
     * @param array $values
     * @return bool
     */
    public function insert(array $values): bool
    {
        return $this->getModel()->insert($values);
    }

    /**
     * @param array|int|string $id
     * @param array $columns
     * @return array|null
     */
    public function find(array|int|string $id, array $columns = ['*']): ?array
    {
        $data = $this->getNewQuery()->find($id, $columns)->get();

        return $data?->toArray();
    }

    /**
     * @param array $columns
     * @param array $where
     * @return array
     */
    public function select(array $columns = ['*'], array $where = []): array
    {
        return $this->getNewQuery()
            ->where($where)
            ->select($columns)
            ->get()->toArray();
    }

    /**
     * @alias Search Engine
     * @param <string, mixed>[] | array[] $where
     * @param <string, mixed>[] | string[] | Closure[] $with
     * @param array{0: string, 1: string} | <string, array{0: integer, 1: integer}[]>[] $order
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginator(
        array $where = [],
        array $with = [],
        array $order = [],
        int   $perPage = 20,
        int   $page = 1,
    ): LengthAwarePaginator
    {
        $cast = $this->model->getCasts();
        $fillable = $this->model->getFillable();
        $query = $this->model->newQuery();

        foreach ($where as $key => $value) if (in_array($key, $fillable)) {

            $snakeKey = Str::snake($key);

            if (array_key_exists($snakeKey, $cast)) {
                $query = match ($cast [$snakeKey]) {
                    'string' => $query->where($snakeKey, 'like', "%$value%"),
                    'datetime' => (function () use ($query, $value, $snakeKey) {
                        /** @var string $value */
                        if (substr_count($value, ',') !== 1) {
                            return $query;
                        }
                        /** @var array{0: string, 1: string} $values */
                        $values = explode(',', $value);
                        return $query->whereBetween($snakeKey, [
                            Carbon::parse(reset($values))->getTimestamp(),
                            Carbon::parse(end($values))->getTimestamp(),
                        ]);
                    })(),
                    'boolean' => $query->where(
                        $snakeKey,
                        '=',
                        is_string($value) ? $value === 'true' : (boolean)$value
                    ),
                    default => $query->where($snakeKey, '=', $value),
                };
            } else {
                $query = $query->where($snakeKey, '=', $value);
            }
        }

        $total = $query->count();

        //  order
        if (!empty($order)) {
            if (array_is_list($order)) {
                $query = $query->orderBy(...$order);
            } else {
                foreach ($order as $field => $direction) {
                    $query = is_array($direction)
                        ? $query->orderBy(reset($direction), end($direction))
                        : $query->orderBy($field, $direction);
                }
            }
        }

        $collections = $query->with($with);

        $collections = $collections->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return new LengthAwarePaginator($collections, $total, $perPage, $page);
    }
}