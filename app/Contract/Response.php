<?php /** @noinspection PhpUnused @formatter:off */
declare(strict_types=1);

namespace App\Contract;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Response as HyperResponse;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

/**
 * @ResponseUtil
 * @\App\Utils\Response\ResponseUtil
 */
final class Response
{

    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response){
        $this->response = $response;
    }

    /**
     * @return HyperResponse
     */
    public function getResponse():Psr7ResponseInterface
    {
        return $this->response->withHeader('Server', 'Swoole');
    }

    /**
     * @param int $status
     * @param array|null $data
     * @param string|null $message
     * @param array|null $header
     * @return Psr7ResponseInterface
     */
    public function message(
        int     $status,
        ?array  $data    = null,
        ?string $message = null,
        ?array  $header  = null,
    ): Psr7ResponseInterface
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
     * @return Psr7ResponseInterface
     */
    public function success(array $data = [], string $message = 'success'): Psr7ResponseInterface
    {
        return $this->message(200, $data, $message);
    }

    /**
     * @param string $Token
     * @param array $data
     * @param string $message
     * @return Psr7ResponseInterface
     */
    public function auth(string $Token, array $data = [], string $message = 'success'): Psr7ResponseInterface
    {
        return $this->message(200, $data, $message, compact('Token'));
    }

    /**
     * @param string $message
     * @return Psr7ResponseInterface
     */
    public function error(string $message = 'error'): Psr7ResponseInterface
    {
        return $this->message(404, message: $message);
    }

    /**
     * @param string $message
     * @return Psr7ResponseInterface
     */
    public function validatorError(string $message = 'error'): Psr7ResponseInterface
    {
        return $this->message(status: 400, message: $message);
    }

    /**
     * @param string $message
     * @return Psr7ResponseInterface
     */
    public function authError(string $message = 'error'): Psr7ResponseInterface
    {
        return $this->message(status: 401, message: $message);
    }

    /**
     * @param string $message
     * @return Psr7ResponseInterface
     */
    public function serverError(string $message = 'error'): Psr7ResponseInterface
    {
        return $this->message(status: 500, message: $message);
    }

    /**
     * @param array $data
     * @return Psr7ResponseInterface
     */
    public function json(array $data): Psr7ResponseInterface
    {
        return $this->getResponse()
            ->withBody(
                new SwooleStream(
                    json_encode($data, JSON_UNESCAPED_UNICODE)
                )
            )->withHeader('content-type', 'application/json; charset=utf-8');
    }
}