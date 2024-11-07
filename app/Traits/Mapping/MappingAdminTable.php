<?php
declare(strict_types=1);

namespace App\Traits\Mapping;

use Carbon\Carbon;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Stringable\Str;
use Psr\Http\Message\ResponseInterface;

/**
 * @MappingAdminTable
 * @\App\Traits\Mapping\MappingAdminTable
 */
trait MappingAdminTable
{

    #[
        RequestMapping(path: 'table', methods: ['POST']),
    ]
    public function table(): ResponseInterface
    {
        $params = $this->request->all();

        $search = $params ['search'] ?? [];
        $order = $params ['order'] ?? [];
        $page = (int)$params ['currentPage'] ?? 1;
        $perPage = (int)$params ['pageSize'] ?? 20;

        $cast = $this->dao->getModel()->getCasts();
        $fillable = $this->dao->getModel()->getFillable();
        $query = $this->dao->getModel()->newQuery();

        foreach ($search as $key => $value) {

            $snakeKey = Str::snake($key);

            if (in_array($snakeKey, $fillable)) continue;

            if (!isset($cast [$snakeKey])) {
                $query = $query->where($snakeKey, '=', $value);
                continue;
            }

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
        }

        $total = $query->count();

        foreach ($order as $key => $value) $query = $query->orderBy($key, $value);

        $list = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get()->toArray();

        return $this->response->success(
            data: compact('total', 'list')
        );
    }
}