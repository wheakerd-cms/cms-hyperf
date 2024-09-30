<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Abstract\AbstractControllerHttp;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

/**
 * @IndexController
 * @\App\Controller\IndexController
 */
#[
    Controller(prefix: '/admin/', server: 'http'),
]
class IndexController extends AbstractControllerHttp
{

    /**
     * @return array
     */
    #[
        RequestMapping(path: 'index', methods: ['GET']),
    ]
    public function index(): array
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
