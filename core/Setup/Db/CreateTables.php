<?php

declare(strict_types=1);

namespace Core\Setup\Db;

use App\Model\Db\Connection as DbConnection;

class CreateTables
{
    /**
     * @var \App\Model\Db\Connection
     */
    private $dbConnection;

    /**
     * @param \App\Model\Db\Connection $dbConnection
     */
    public function __construct(
        DbConnection $dbConnection
    ) {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $this->checkDatabase();

        $this->dbConnection->get()
            ->exec(<<<SQL
CREATE TABLE `subscribers` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
SQL);
        $this->dbConnection->get()
            ->exec(<<<SQL
CREATE TABLE `cache` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(255) NOT NULL UNIQUE,
    `value` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
SQL);
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function checkDatabase()
    {
        $tablesToCheck = ['subscribers', 'cache'];
        $placeholders = implode(',', array_fill(0, count($tablesToCheck), '?'));
        $sql = "SELECT COUNT(TABLE_NAME) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN ({$placeholders})";
        $stmt = $this->dbConnection->get()->prepare($sql);

        if (! $stmt) {
            throw new \Exception('Failed to prepare SQL statement.');
        }

        if (! $stmt->execute($tablesToCheck)) {
            $errorInfo = $stmt->errorInfo();
            throw new \Exception('Failed to execute SQL statement: ' . $errorInfo[2]);
        }

        if ($stmt->fetchColumn()) {
            throw new \Exception('One or more of the specified tables already exist.');
        }
    }
}