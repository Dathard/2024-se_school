<?php

declare(strict_types=1);

namespace App\Model\Db;

use PDO;

class Connection
{
    private $connection;

    /**
     * @var \App\Model\Config
     */
    private $config;

    /**
     * @param \App\Model\Config $config
     */
    public function __construct(
        \App\Model\Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @return \PDO
     */
    public function get(): PDO
    {
         if (! $this->connection) {
             $dbConfig = $this->config->getDbCredentials();

             $this->connection = new PDO(
                 "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}",
                 $dbConfig['user'],
                 $dbConfig['password'],
                 [
                     PDO::ATTR_TIMEOUT => 5, // 5 seconds timeout
                     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                 ]
             );
         }

         return $this->connection;
     }
}