<?php
declare(strict_types=1);

namespace App\Controller\Http\Api;

use App\Abstract\AbstractControllerHttp;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Iconify\IconsJSON\Finder;
use Psr\Http\Message\ResponseInterface;
use function json_validate;

/**
 * @ResourceController
 * @\App\Controller\Http\Api\ResourceController
 */
#[
    Controller(prefix: '/api/resource'),
]
class ResourceController extends AbstractControllerHttp
{

    /**
     * @param string $prefix
     * @return array|ResponseInterface
     * @api /api/resource/iconify/{prefix}.json
     */
    #[
        RequestMapping(path: 'iconify/{prefix}.json', methods: ['GET']),
    ]
    public function iconify(string $prefix): array|ResponseInterface
    {
        $icons = $this->request->query('icons');
        $iconSets = Finder::collections();

        if (is_null($iconSets) || !array_key_exists($prefix, $iconSets)) {
            return $this->response->error();
        }

        $contents = file_get_contents(Finder::locate($prefix));

        if (!json_validate($contents)) {
            return $this->response->error();
        }

        $json = json_decode($contents, true);

        $aliases = $json ['aliases'] [$icons] ?? [];
        var_dump($aliases,$icons);
        $name = empty($aliases) ? $icons : $aliases ['parent'];
        empty($aliases) || $aliases = [$icons => $aliases];

        var_dump(
            $json ['icons'] [$name]
        );

        $body = $json ['icons'] [$name] ?? [];

        $lastModified = $json ['lastModified'];

        $icons = [$icons => $body];

        $data = compact('prefix', 'lastModified', 'aliases', 'lastModified', 'icons');

        isset($json['width']) && $data ['width'] = $json ['width'];
        isset($json['height']) && $data ['height'] = $json ['height'];

        return $data;
    }
}