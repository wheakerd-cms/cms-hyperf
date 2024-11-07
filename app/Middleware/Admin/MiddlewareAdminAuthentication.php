<?php
declare(strict_types=1);

namespace App\Middleware\Admin;

use App\Contract\Response;
use App\Dao\Admin\DaoAdminAdministrator;
use App\Security\SecurityAdminJws;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @MiddlewareAdminAuthentication
 * @\App\Middleware\Admin\MiddlewareAdminAuthentication
 */
class MiddlewareAdminAuthentication implements MiddlewareInterface
{

    #[Inject]
    protected Response $response;
    #[Inject]
    protected SecurityAdminJws $componentAdminJWS;
    #[Inject]
    protected DaoAdminAdministrator $daoAdminAdministrator;

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Token');

        if (empty($token) || !$this->componentAdminJWS->check($token)) {
            return $this->response->authError('未登录或者登录已过期');
        }

        $tokenInfo = $this->componentAdminJWS->getPayload($token);

        $userinfo = $this->daoAdminAdministrator->findById($tokenInfo->id);

        if (empty($userinfo)) {
            return $this->response->authError('账户不存在');
        }

        if (!$userinfo ['status']) {
            return $this->response->authError('该账户已被封禁');
        }

        Context::set('userinfo', (object)$userinfo);

        return $handler->handle($request);
    }
}