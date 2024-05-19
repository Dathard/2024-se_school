<?php

declare(strict_types=1);

namespace App\Model\Controller\Result;

use App\Model\Controller\ResultInterface;

class Raw implements ResultInterface
{
    /**
     * @var int
     */
    private $httpResponseCode = 200;

    /**
     * @var string
     */
    private $content = '';

    /**
     * @param $httpCode
     * @return $this
     */
    public function setHttpResponseCode($httpCode)
    {
        $this->httpResponseCode = $httpCode;
        return $this;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return $this
     */
    public function render()
    {
        http_response_code($this->httpResponseCode);
        echo $this->content;

        return $this;
    }
}