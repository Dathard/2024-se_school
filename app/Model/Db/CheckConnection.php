<?php

declare(strict_types=1);

namespace App\Model\Db;

use PDO;

class CheckConnection
{
    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @return void
     * @throws \Exception
     */
    public function execute(string $host, string $user, string $password, string $database): void
    {
        $options = [
            PDO::ATTR_TIMEOUT => 5, // 5 seconds timeout
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        new PDO("mysql:host=$host;dbname=$database", $user, $password, $options);
    }
}