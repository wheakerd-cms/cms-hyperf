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
use Hyperf\Database\Model\Model;
use Hyperf\Paginator\LengthAwarePaginator;
use Hyperf\Stringable\Str;

/**
 * @AbstractDao
 * @\App\Abstract\AbstractDao
 */
abstract class AbstractDao
{

    /**
     * @var Model $model
     */
    protected Model $model;

    abstract public function __construct(Model $model);

    public function getModel(): Model
    {
        return $this->model;
    }

    public function exist(array $where = []): bool
    {
        return $this->model->newQuery()->where($where)->exists();
    }

    /**
     * @param array|int|string $id
     * @param array $columns
     * @return array
     */
    public function find(array|int|string $id, array $columns = ['*']): array
    {
        return $this->model->newQuery()->find($id, $columns)->toArray();
    }

    /**
     * @param array $where
     * @param array $columns
     * @return array|null
     */
    public function findOne(array $where, array $columns = ['*']): array|null
    {
        return $this->model->newQuery()
            ->where($where)
            ->first($columns)
            ?->toArray();
    }

    /**
     * @param string $column
     * @param int|array $where
     * @return int|string|bool|null
     */
    public function value(string $column, int|array $where): null|int|string|bool
    {
        if (is_array($where)) {
            return $this->model->newQuery()
                ->where($where)
                ->first()
                ?->value($column);
        }

        return $this->model->newQuery()->find($where)->value($column);
    }

    public function select(array $columns = ['*']): array
    {
        return $this->model->newQuery()
            ->select($columns)->get()->toArray();
    }

    public function insert(array $inputs): bool
    {
        return $this->model->newInstance()
            ->fill($inputs)->save();
    }

    public function insertAll(array $inputs): bool
    {
        $data = [];
        foreach ($inputs as $input) {
            $model = $this->model->newInstance();

            if ($model->usesTimestamps()) {
                $model->updateTimestamps();
            }

            $data[] = $model->fill($input)->getAttributes();
        }

        return $this->model->newInstance()->insert($data);
    }

    public function insertGetId(array $inputs): int
    {
        $model = $this->model->newInstance();

        if ($model->usesTimestamps()) {
            $model->updateTimestamps();
        }

        return $this->model->newInstance()->insertGetId(
            $model->fill($inputs)->getAttributes()
        );
    }

    public function update(int|array $where, array $data): bool
    {
        return (boolean)$this->model->newQuery()->where(fn(Builder $query) => is_array($where)
            ? $query->where($where)
            : $query->where($this->model->getKeyName(), $where)
        )->update(
            $this->model->fill($data)->getDirty()
        );
    }

    /**
     * @param integer|array<integer[]> $ids
     * @return bool
     */
    public function delete(int|array $ids): bool
    {
        $model = $this->model->newQuery();

        if (is_int($ids)) {
            $model = $model->where(
                $this->model->getKeyName(), $ids
            );
        } else {
            $model = $model->whereIn(
                $this->model->getKeyName(),
                $ids
            );
        }

        return (boolean)$model->delete();
    }

    /**
     * @param array $where
     * @return bool
     */
    public function whereDelete(array $where): bool
    {
        return (boolean)$this->model->newQuery()->where($where)->delete();
    }

    /**
     * @alias Search Engine
     * @param array{0: integer, 1: integer} $ranks
     * @param array-key<string>[] $params
     * @param <string, mixed>[] | array[] $where
     * @param string[] $hidden
     * @param <string, mixed>[] | string[] | Closure[] $with
     * @param array{0: integer, 1: integer} | <string, array{0: integer, 1: integer}[]>[] $order
     * @return LengthAwarePaginator|array
     */
    public function paginator(
        array $ranks = [],
        array $params = [],
        array $where = [],
        array $hidden = [],
        array $with = [],
        array $order = [],
    ): LengthAwarePaginator|array
    {
        $cast = $this->model->getCasts();
        $fillable = $this->model->getFillable();
        $query = $this->model->newQuery();

        foreach ($params as $key => $value) if (in_array($key, $fillable)) {

            $snakeKey = Str::snake($key);

            if (array_key_exists($snakeKey, $cast)) {
                $query = match ($cast [$snakeKey]) {
                    'string' => $query->where($snakeKey, 'like', "%$value%"),
                    'datetime' => (function () use ($query, $value, $snakeKey) {
                        if (substr_count($value, ',') !== 1) {
                            return $query;
                        }
                        /**
                         * @var array{0: string, 1: string} $values
                         */
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

        $query = $query->where($where);

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

        if (empty($ranks)) {
            return $query->get()->makeHidden($hidden)->toArray();
        }

        list($page, $perPage) = $ranks;
        $collections = $collections->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get()->makeHidden($hidden);

        return new LengthAwarePaginator($collections, $total, $perPage, $page);
    }
}