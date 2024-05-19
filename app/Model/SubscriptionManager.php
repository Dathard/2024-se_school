<?php

declare(strict_types=1);

namespace App\Model;

class SubscriptionManager
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
     * @return void
     */
    public function subscribe(string $email): void
    {
        $this->dbConnection->get()
            ->exec("INSERT INTO subscribers (email) VALUES ('$email')");
    }
}