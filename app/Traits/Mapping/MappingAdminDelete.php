<?php
declare(strict_types=1);

namespace App\Traits\Mapping;

use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @MappingAdminDelete
 * @\App\Traits\Mapping\MappingAdminDelete
 */
trait MappingAdminDelete
{
    #[
        RequestMapping(path: 'delete', methods: ['POST']),
    ]
    public function delete(): ResponseInterface
    {
        $ids = $this->request->post('ids', []);

        $deleted = (bool)$this->dao->getModel()->destroy($ids);

        end:
        return $deleted ? $this->response->success() : $this->response->error();
    }
}