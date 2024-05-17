<?php

declare(strict_types=1);

namespace App\Model\Cli\Command;

use App\Model\Cli\AbstractCommand;

class Install extends AbstractCommand
{
    public const CLI_PARAMETER_DB_HOST = 'db-host';
    public const CLI_PARAMETER_DB_NAME = 'db-name';
    public const CLI_PARAMETER_DB_USER = 'db-user';
    public const CLI_PARAMETER_DB_PASSWORD = 'db-password';

    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function execute(array $params)
    {
        if (in_array(AbstractCommand::CLI_PARAMETER_HELP, $params)) {
            print_r($this->help());
            exit(0);
        }

        $this->validate($params);


        // TODO: Create database ...
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
}