<?php
declare(strict_types=1);

namespace App\Traits\Mapping;

use App\Utils\HelperFunctions;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Stringable\Str;
use Psr\Http\Message\ResponseInterface;

/**
 * @MappingAdminSelect
 * @\App\Traits\Mapping\MappingAdminSelect
 */
trait MappingAdminSelect
{
    #[
        RequestMapping(path: 'select', methods: ['GET']),
    ]
    public function select(): ResponseInterface
    {
        $data = $this->dao->getModel()->newQuery()->select()->get()->toArray();

        if (isset($this->dao->getModel()->parentIdAttribute)) {
            $parentKey = Str::camel(
                $this->dao->getModel()->parentIdAttribute
            );

            $data = HelperFunctions::listToTree($data, $this->dao->getModel()->getKeyName(), $parentKey);
        }

        return $this->response->success($data);
    }
}