<?php
declare(strict_types=1);

namespace App\Traits\Mapping;

use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @MappingAdminList
 * @\App\Traits\Mapping\MappingAdminList
 */
trait MappingAdminList
{
    #[
        RequestMapping(path: 'list', methods: ['GET']),
    ]
    public function list(): ResponseInterface
    {
        $with = $this->dao->getModel()->relationship ?? [];

        $data = $this->dao->getModel()->newQuery()->with($with)->select()->get()->toArray();

        return $this->response->success($data);
    }
}