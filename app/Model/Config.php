<?php

declare(strict_types=1);

namespace App\Model;

class Config
{
    public const CONFIG_FILE_NAME = 'env.json';

    /**
     * @return array
     * @throws \Exception
     */
    public function get(): array
    {
        if (! file_exists(BP . '/app/etc/' . self::CONFIG_FILE_NAME)) {
            throw new \Exception('Application is not configured. Please run the command: php bin/run install');
        }

        $serviceConfigJson = file_get_contents(BP . '/app/etc/' . self::CONFIG_FILE_NAME);

        return json_decode($serviceConfigJson, true);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getDbCredentials(): array
    {
        return $this->get()['db'];
    }
}