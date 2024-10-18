<?php
declare(strict_types=1);

namespace App\Middleware\Admin;

use App\Dao\Admin\DaoAdministrator;
use App\Security\Admin\SecurityAdminJws;
use App\Utils\Response\ResponseUtil;
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

    /**
     * @var ResponseUtil $responseUtil
     */
    #[Inject]
    protected ResponseUtil $responseUtil;

    public function __construct(
        protected SecurityAdminJws $componentAdminJWS,
        protected DaoAdministrator $daoAdminAdministrator,
    )
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Token');

        if (empty($token) || !$this->componentAdminJWS->check($token)) {
            return $this->responseUtil->authError('未登录或者登录已过期');
        }

        $tokenInfo = $this->componentAdminJWS->getPayload($token);

        $userinfo = $this->daoAdminAdministrator->findById($tokenInfo->id);

        if (empty($userinfo)) {
            return $this->responseUtil->authError('账户不存在');
        }

        if (!$userinfo ['status']) {
            return $this->responseUtil->authError('该账户已被封禁');
        }

        Context::set('userinfo', (object)$userinfo);

        return $handler->handle($request);
    }
}