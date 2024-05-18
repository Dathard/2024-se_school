<?php

declare(strict_types=1);

namespace App\Model\Controller;

class Request
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var mixed
     */
    private $method;

    /**
     * @return string
     */
    public function getUri(): string
    {
        if (empty($this->uri) && !empty($_SERVER['REQUEST_URI']))
        {
            $uri = $_SERVER['REQUEST_URI'];
            $uri = preg_replace('#^/index\.php#', '', $uri);
            $this->uri = rtrim($uri, '/');
        }

        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        if ($this->method === null) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }

        return $this->method;
    }
}