<?php

namespace App\Model\Controller;

interface ResultInterface
{
    public function setHttpResponseCode($httpCode);

    public function render();
}