<?php
declare(strict_types=1);

namespace App\Traits\Mapping;

use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @MappingAdminSave
 * @\App\Traits\Mapping\MappingAdminSave
 */
trait MappingAdminSave
{
    #[
        RequestMapping(path: 'save', methods: ['POST']),
    ]
    public function save(): ResponseInterface
    {
        $inputs = $this->request->all();

        $attributes = $this->dao->getModel()->fill($inputs)->getAttributes();
        $key = $this->dao->getModel()->getKeyName();
        $query = $this->dao->getModel()->newQuery();

        /** @var int $id */
        if (isset($attributes [$key])) {
            $id = $attributes [$key];
            unset($attributes [$key]);
            $saved = (bool)$query->where($key, $id)->update($attributes);
            goto end;
        }

        $saved = $this->dao->getModel()->fill($attributes)->save();

        end:
        return $saved ? $this->response->success() : $this->response->error();
    }
}