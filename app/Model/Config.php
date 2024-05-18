<?php

declare(strict_types=1);

namespace App\Model;

class Config
{
    public const CONFIG_FILE_NAME = 'env.json';

    /**
     * @return array
     */
    public function get(): array
    {
        $serviceConfigJson = file_get_contents(BP . '/app/etc/' . self::CONFIG_FILE_NAME);

        return json_decode($serviceConfigJson, true);
    }

    /**
     * @return array
     */
    public function getDbCredentials(): array
    {
        return $this->get()['db'];
    }
}