<?php

declare(strict_types=1);

namespace Core\Cli\Command;

use Core\Cli\CommandInterface;

class Install implements CommandInterface
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
        if (in_array(CommandInterface::CLI_PARAMETER_HELP, $params)) {
            print_r($this->help());
            exit(0);
        }

        $this->validate($params);

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
     * @return array[]
     */
    public function help(): array
    {
        return [
            [
                'command' => "--" . self::CLI_PARAMETER_DB_HOST,
                'description' => "The database server’s fully qualified hostname or IP address.",
                'is_required' => true,
            ],
            [
                'command' => "--" . self::CLI_PARAMETER_DB_NAME,
                'description' => "Name of the database instance in which you want to install the database tables.",
                'is_required' => true,
            ],
            [
                'command' => "--" . self::CLI_PARAMETER_DB_USER,
                'description' => "Username of the database instance owner.",
                'is_required' => true,
            ],
            [
                'command' => "--" . self::CLI_PARAMETER_DB_PASSWORD,
                'description' => "Database instance owner’s password.",
                'is_required' => true,
            ]
        ];
    }

    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    private function validate(array $params)
    {
        if (! array_key_exists(self::CLI_PARAMETER_DB_HOST, $params)) {
            throw new \Exception(
                "The DB host parameter was not set." . PHP_EOL .
                "More details about the required parameters: " . PHP_EOL .
                print_r($this->help(), true)
            );
        }

        if (! array_key_exists(self::CLI_PARAMETER_DB_NAME, $params)) {
            throw new \Exception(
                "The DB name parameter was not set." . PHP_EOL .
                "More details about the required parameters: " . PHP_EOL .
                print_r($this->help(), true)
            );
        }

        if (! array_key_exists(self::CLI_PARAMETER_DB_USER, $params)) {
            throw new \Exception(
                "The DB user parameter was not set." . PHP_EOL .
                "More details about the required parameters: " . PHP_EOL .
                print_r($this->help(), true)
            );
        }

        if (! array_key_exists(self::CLI_PARAMETER_DB_PASSWORD, $params)) {
            throw new \Exception(
                "The DB password parameter was not set." . PHP_EOL .
                "More details about the required parameters: " . PHP_EOL .
                print_r($this->help(), true)
            );
        }
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