<?php
namespace Mustafa\PhpWebserver;

use Mustafa\PhpWebserver\Request;
use Mustafa\PhpWebserver\Exception;

class Server
{
    /**
     * @var null
     */
    protected $port = null;

    /**
     * @var null
     */
    protected $host = null;

    /**
     * @var null
     */
    protected $socket = null;

    /**
     * @param null $host
     * @param null $port
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = (int)$port;

        $this->connect();
    }

    protected function createSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
    }

    protected function bind()
    {
        if (!socket_bind($this->socket, $this->host, $this->port)) {
            throw new \Exception('could not bind: '
                . $this->host . ':'
                . $this->port . ' - '
                . socket_strerror(socket_last_error())
            );
        }
    }

    protected function connect()
    {
        $this->createSocket();

        $this->bind();
    }

    public function listen($callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception('Callback must be callable');
        }

        while (1) {
            socket_listen($this->socket);

            if (!$client = socket_accept($this->socket)) {
                socket_close($client);
                continue;
            }

            $request = Request::withHeaderString(socket_read($client, 1024));

            $response = call_user_func($callback, $request);

            if (!$response || !$response instanceof Response) {
                $response = Response::error(404);
            }

            $response = (string)$response;

            socket_write($client, $response, strlen($response));

            socket_close($client);
        }
    }
}