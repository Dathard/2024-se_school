<?php

declare(strict_types=1);

namespace App\Cli;

use Core\Cli\CommandInterface;

class SendEmailNotificationsCommand implements CommandInterface
{
    /**
     * @var \App\Model\Integration\Nbu
     */
    private $nbuIntegration;

    /**
     * @var \App\Model\SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * @param \App\Model\Integration\Nbu $nbuIntegration
     * @param \App\Model\SubscriptionRepository $subscriptionRepository
     */
    public function __construct(
        \App\Model\Integration\Nbu $nbuIntegration,
        \App\Model\SubscriptionRepository $subscriptionRepository
    ) {
        $this->nbuIntegration = $nbuIntegration;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function execute(array $params)
    {
        $subscribers = $this->subscriptionRepository->getList();

        if (empty($subscribers)) {
            return;
        }

        $subscribedEmails = array_column($subscribers, 'email');

        mail(
            implode(',', $subscribedEmails),
            'Актуальний курс USD станом на ' . date('d.m.Y H:i'),
            'Курс USD - ' . $this->nbuIntegration->getRate('USD') . ' грн.'
        );
    }
}