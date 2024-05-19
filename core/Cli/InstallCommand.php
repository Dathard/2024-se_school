<?php

declare(strict_types=1);

namespace Core\Cli;

class InstallCommand implements CommandInterface
{
    public const CLI_PARAMETER_DB_HOST = 'db-host';
    public const CLI_PARAMETER_DB_NAME = 'db-name';
    public const CLI_PARAMETER_DB_USER = 'db-user';
    public const CLI_PARAMETER_DB_PASSWORD = 'db-password';

    /**
     * @var \App\Model\Db\CheckConnection
     */
    private $checkDbConnection;

    /**
     * @var \Core\Setup\Db\CreateTables
     */
    private $createDbTables;

    /**
     * @var \Core\Setup\CreateConfig
     */
    private $createConfig;

    /**
     * @param \App\Model\Db\CheckConnection $checkDbConnection
     * @param \Core\Setup\Db\CreateTables $createDbTables
     * @param \Core\Setup\CreateConfig $createConfig
     */
    public function __construct(
        \App\Model\Db\CheckConnection $checkDbConnection,
        \Core\Setup\Db\CreateTables $createDbTables,
        \Core\Setup\CreateConfig $createConfig
    ) {
        $this->checkDbConnection = $checkDbConnection;
        $this->createDbTables = $createDbTables;
        $this->createConfig = $createConfig;
    }

    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function execute(array $params)
    {
        $this->checkDbConnection->execute(
            $params[self::CLI_PARAMETER_DB_HOST],
            $params[self::CLI_PARAMETER_DB_USER],
            $params[self::CLI_PARAMETER_DB_PASSWORD],
            $params[self::CLI_PARAMETER_DB_NAME]
        );

        $this->createConfig->execute($this->prepareConfig($params));
        $this->createDbTables->execute();
    }

    /**
     * @param array $params
     * @return array[]
     */
    private function prepareConfig(array $params): array
    {
        return [
            'db' => [
                'host' => $params[self::CLI_PARAMETER_DB_HOST],
                'user' => $params[self::CLI_PARAMETER_DB_USER],
                'password' => $params[self::CLI_PARAMETER_DB_PASSWORD],
                'dbname' => $params[self::CLI_PARAMETER_DB_NAME]
            ]
        ];
    }
}