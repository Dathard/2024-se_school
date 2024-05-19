<?php

declare(strict_types=1);

namespace App\Api;

use App\Model\Controller\ResultInterface;

class Subscribe
{
    /**
     * @var \App\Model\Controller\Request
     */
    private $request;

    /**
     * @var \App\Model\Controller\Result\Raw
     */
    private $rawResult;

    /**
     * @var \App\Model\Validator\EmailAddress
     */
    private $emailAddressValidator;

    /**
     * @var \App\Model\SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * @var \App\Model\SubscriptionManager
     */
    private $subscriptionManager;

    /**
     * @param \App\Model\Controller\Request $request
     * @param \App\Model\Controller\Result\Raw $rawResult
     * @param \App\Model\Validator\EmailAddress $emailAddressValidator
     * @param \App\Model\SubscriptionRepository $subscriptionRepository
     * @param \App\Model\SubscriptionManager $subscriptionManager
     */
    public function __construct(
        \App\Model\Controller\Request $request,
        \App\Model\Controller\Result\Raw $rawResult,
        \App\Model\Validator\EmailAddress $emailAddressValidator,
        \App\Model\SubscriptionRepository $subscriptionRepository,
        \App\Model\SubscriptionManager $subscriptionManager
    ) {
        $this->request = $request;
        $this->rawResult = $rawResult;
        $this->emailAddressValidator = $emailAddressValidator;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @return \App\Model\Controller\ResultInterface
     */
    public function execute(): ResultInterface
    {
        $postParams = $this->request->getPostParams();
        if (! array_key_exists('email', $postParams)) {
            return $this->rawResult->setContent('Email parameter is missing from the request.')
                ->setHttpResponseCode(400);
        }

        $email = $postParams['email'];
        if (! $this->emailAddressValidator->isValid($email)) {
            return $this->rawResult->setContent('Invalid email address format.')
                ->setHttpResponseCode(400);
        }

        try {
            $this->subscriptionRepository->getByEmail($email);
            return $this->rawResult->setContent('Email is already subscribed.')
                    ->setHttpResponseCode(409);
        } catch (\Exception $e) {}

        $this->subscriptionManager->subscribe($email);

        return $this->rawResult->setContent('Email has been successfully subscribed.')
            ->setHttpResponseCode(200);
    }
}