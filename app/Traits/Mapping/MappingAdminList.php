<?php
declare(strict_types=1);

namespace App\Traits\Mapping;

use App\Utils\HelperFunctions;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Stringable\Str;
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

        if (isset($this->dao->getModel()->parentIdAttribute)) {
            $parentKey = Str::camel(
                $this->dao->getModel()->parentIdAttribute
            );

            $data = HelperFunctions::listToTree($data, $this->dao->getModel()->getKeyName(), $parentKey);
        }

        return $this->response->success($data);
    }
}