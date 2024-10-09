<?php /** @noinspection PhpUnused @formatter:off */
declare(strict_types=1);

namespace App\Utils\Response;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\MessageInterface;

/**
 * @ResponseUtil
 * @\App\Utils\Response\ResponseUtil
 */
final class ResponseUtil
{

    /**
     * @var ResponseInterface $response
     */
    #[Inject]
    protected ResponseInterface $response;

    /**
     * @return Response
     */
    public function getResponse():MessageInterface
    {
        return $this->response->withHeader('Server', 'Swoole');
    }

    /**
     * @param int $status
     * @param array|null $data
     * @param string|null $message
     * @param array|null $header
     * @return MessageInterface
     */
    public function message(
        int     $status,
        ?array  $data    = null,
        ?string $message = null,
        ?array  $header  = null,
    ): MessageInterface
    {
        $response = $this->getResponse()->json(
            array_filter(
                compact('message', 'data'),
                fn($value) => !is_null($value)
            )
        )->withStatus($status);

        if (is_array($header)) foreach ($header as $key => $value) {
            $response = $response->withHeader($key, $value);
        }

        return $response;
    }

    /**
     * @code 200
     * @param array $data
     * @param string $message
     * @return MessageInterface
     */
    public function success(array $data = [], string $message = 'success'): MessageInterface
    {
        return $this->message(200, $data, $message);
    }

    /**
     * @param string $Token
     * @param array $data
     * @param string $message
     * @return MessageInterface
     */
    public function auth(string $Token, array $data = [], string $message = 'error'): MessageInterface
    {
        return $this->message(200, $data, $message, compact('Token'));
    }

    /**
     * @param array $data
     * @param string $message
     * @return MessageInterface
     */
    public function error(array $data = [], string $message = 'error'): MessageInterface
    {
        return $this->message(404, $data, $message);
    }

    /**
     * @param string $message
     * @return MessageInterface
     */
    public function validatorError(string $message = 'error'): MessageInterface
    {
        return $this->message(status: 400, message: $message);
    }

    /**
     * @param string $message
     * @return MessageInterface
     */
    public function authError(string $message = 'error'): MessageInterface
    {
        return $this->message(status: 401, message: $message);
    }

    /**
     * @param string $message
     * @return MessageInterface
     */
    public function serverError(string $message = 'error'): MessageInterface
    {
        return $this->message(status: 500, message: $message);
    }

    /**
     * @param array $data
     * @return MessageInterface
     */
    public function json(array $data): MessageInterface
    {
        return $this->getResponse()
            ->withBody(
                new SwooleStream(
                    json_encode($data, JSON_UNESCAPED_UNICODE)
                )
            )->withHeader('content-type', 'application/json; charset=utf-8');
    }
}