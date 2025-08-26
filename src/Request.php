<?php
namespace Mustafa\PhpWebserver;

class Request
{
    /**
     * @var null
     */
    protected $method = null;

    /**
     * @var null
     */
    protected $uri = null;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param null $method
     * @param null $uri
     * @param array $headers
     */
    public function __construct($method, $uri, array $headers)
    {
        $this->method = strtoupper($method);
        $this->headers = $headers;

        @list($this->uri, $params) = explode('?', $uri);

        parse_str($params, $this->params);
    }

    /**
     * @return string|null
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed|string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getHeaders($key, $default = null)
    {
        if (! isset($this->headers[$key])) {
            return $default;
        }

        return $this->headers[$key];
    }

    /**
     * @return array
     */
    public function getParams($key, $default = null)
    {
        if (! isset($this->params[$key])) {
            return $default;
        }

        return $this->params[$key];
    }

    /**
     * @param $header
     * @return static
     */
    public static function withHeaderString($header)
    {
        $lines = explode("\n", $header);

        list($method, $uri) = explode(' ', array_shift($lines));

        $headers = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        return new static($method, $uri, $headers);
    }
}