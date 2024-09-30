<?php
declare(strict_types=1);

namespace App\Abstract;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\WebSocketServer\Sender;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * @AbstractControllerHttp
 * @\App\Abstract\AbstractControllerHttp
 */
abstract class AbstractControllerWebSocket implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{

    /**
     * @var Sender $sender
     */
    #[Inject]
    protected Sender $sender;

    /**
     * @param Server $server
     * @param Request $request
     */
    abstract public function onOpen($server, $request): void;

    /**
     * @param \Swoole\Server $server
     */
    abstract public function onClose($server, int $fd, int $reactorId): void;

    /**
     * @param Server $server
     * @param Frame $frame
     */
    abstract public function onMessage($server, $frame): void;


    /**
     * @param int $fd
     * @param string $content
     * @return void
     */
    private function send(int $fd, string $content): void
    {
        $this->sender->push($fd, $content);
    }

    /**
     * @param int $fd
     * @param int $code
     * @param array $data
     * @param string|null $message
     * @return void
     */
    protected function success(int $fd, int $code, array $data, ?string $message = 'success'): void
    {
        $status = 200;

        $content = array_filter(
            compact('code', 'data', 'message', 'status'),
        );

        $this->send($fd, json_encode($content));
    }

    /**
     * @param int $fd
     * @param int $code
     * @param string $message
     * @return void
     */
    protected function error(int $fd, int $code, string $message = 'error'): void
    {
        $status = 404;

        $content = array_filter(
            compact('code', 'message', 'status'),
        );

        $this->send($fd, json_encode($content));
    }

    /**
     * @param int $fd
     * @param int $code
     * @param string $message
     * @return void
     */
    protected function auth(int $fd, int $code, string $message): void
    {
        $status = 401;

        $content = array_filter(
            compact('code', 'message', 'status'),
        );

        $this->send($fd, json_encode($content));
    }

    /**
     * @param int $fd
     * @param int $code
     * @param string $message
     * @return void
     */
    protected function validate(int $fd, int $code, string $message): void
    {
        $status = 400;

        $content = array_filter(
            compact('code', 'message', 'status'),
        );

        $this->send($fd, json_encode($content));
    }
}