<?php
/**
 * @noinspection PhpUnused
 * @noinspection DuplicatedCode
 */
declare(strict_types=1);

namespace App\Abstract;

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
     * @return AbstractModel
     */
    public function getModel(): AbstractModel
    {
        return $this->model;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function save(array $attributes): bool
    {
        $key = $this->model->getKeyName();

        /** @var int $id */
        if (isset($attributes [$key])) {
            $id = $attributes [$key];
            unset($attributes [$key]);
            return $this->update($id, $attributes);
        }

        return $this->getModel()->fill($attributes)->save();
    }

    /**
     * @param array|int $id
     * @param array $attributes
     * @return bool
     */
    public function update(array|int $id, array $attributes): bool
    {
        $query = $this->getModel()->newQuery();

        $query = is_array($id) ? $query->where($id) : $query->where($this->model->getKeyName(), $id);

        $values = $this->getModel()->fill($attributes)->getAttributes();

        return (bool)$query->update($values);
    }

    /**
     * @param array $where
     * @return bool
     */
    public function delete(array $where): bool
    {
        return $this->getModel()->newQuery()->where($where)->delete();
    }

    /**
     * @param array<integer> $ids
     * @return bool
     */
    public function destroy(array $ids): bool
    {
        return (boolean)$this->getModel()->destroy($ids);
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
        $query = $this->getModel()->query();

        $data = $query->find($id, $columns);

        return $data?->toArray();
    }

    /**
     * @param array $where
     * @param array $columns
     * @return array
     */
    public function select(array $where = [], array $columns = ['*']): array
    {
        return $this->getModel()->newQuery()
            ->where($where)
            ->select($columns)
            ->get()->toArray();
    }
}