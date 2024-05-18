<?php

declare(strict_types=1);

namespace App\Model;

class SubscriptionRepository
{
    /**
     * @var \App\Model\Db\Connection
     */
    private $dbConnection;

    /**
     * @param \App\Model\Db\Connection $dbConnection
     */
    public function __construct(
        \App\Model\Db\Connection $dbConnection
    ) {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @param string $email
     * @return array
     * @throws \Exception
     */
    public function getByEmail(string $email): array
    {
         $subscriber = $this->dbConnection->get()
            ->query("SELECT * FROM `subscribers` WHERE `email` = '{$email}'")
            ->fetch(\PDO::FETCH_ORI_FIRST);

         if (! $subscriber) {
            throw new \Exception('Subscriber with the provided email address does not exist.');
         }

         return $subscriber;
    }
}