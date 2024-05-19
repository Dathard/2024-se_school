<?php

declare(strict_types=1);

namespace App\Api;

use App\Model\Controller\ResultInterface;

class Rate
{
    /**
     * @var \App\Model\Integration\Nbu
     */
    private $nbuIntegration;

    /**
     * @var \App\Model\Controller\Result\Raw
     */
    private $rawResult;

    /**
     * @param \App\Model\Integration\Nbu $nbuIntegration
     * @param \App\Model\Controller\Result\Raw $rawResult
     */
    public function __construct(
        \App\Model\Integration\Nbu $nbuIntegration,
        \App\Model\Controller\Result\Raw $rawResult
    ) {
        $this->nbuIntegration = $nbuIntegration;
        $this->rawResult = $rawResult;
    }

    /**
     * @return \App\Model\Controller\ResultInterface
     */
    public function get(): ResultInterface
    {
        try {
            $this->rawResult->setContent((string) $this->nbuIntegration->getRate('USD'));
            $this->rawResult->setHttpResponseCode(200);
        } catch (\Exception $e) {
            $this->rawResult->setContent($e->getMessage());
            $this->rawResult->setHttpResponseCode(500);
        }

        return $this->rawResult;
    }
}