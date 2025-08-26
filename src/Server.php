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

        if ($this->socket === false) {
            throw new Exception(
                "Could not create socket: " .
                socket_strerror(socket_last_error())
            );
        }

        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
    }

    protected function bind()
    {
        if (!socket_bind($this->socket, $this->host, $this->port)) {
            throw new Exception('Could not bind: '
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
            throw new Exception('Callback must be callable');
        }

        if (!socket_listen($this->socket, 5)) {
            throw new Exception(
                "Could not listen on socket: " .
                socket_strerror(socket_last_error($this->socket))
            );
        }

        while (true) {
            $client = socket_accept($this->socket);
            if ($client === false) {
                continue;
            }

            $request = socket_read($client, 2048);
            if ($request === false) {
                socket_close($client);
                continue;
            }

            try {
                $request = Request::withHeaderString($request);
                $response = call_user_func($callback, $request);

                if (!$response || !$response instanceof Response) {
                    $response = Response::error(404);
                }

                socket_write($client, (string) $response, strlen((string) $response));
            } catch (\Exception $e) {
                $errorResponse = Response::error(500);
                socket_write($client, (string) $errorResponse, strlen((string) $errorResponse));
            }

            socket_close($client);
        }
    }
}