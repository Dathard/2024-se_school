<?php

declare(strict_types=1);

namespace Core\Setup;

use App\Model\Config;

class CreateConfig
{
    /**
     * @param array $config
     * @return void
     * @throws \Exception
     */
    public function execute(array $config)
    {
        if (file_exists(BP . '/app/etc/' . Config::CONFIG_FILE_NAME)) {
            throw new \Exception("Application already configured.\nPlease check config file " . BP . '/app/etc/' . Config::CONFIG_FILE_NAME);
        }

        $config = $this->prepareConfig($config);
        file_put_contents(BP . '/app/etc/' . Config::CONFIG_FILE_NAME, json_encode($config));
    }

    /**
     * @param array $config
     * @return array[]
     * @throws \Exception
     */
    private function prepareConfig(array $config): array
    {
        if (! isset($config['db'])) {
            throw new \Exception('Service configuration error. DB settings are not set.');
        }

        $preparedConfig = ['db' => []];

        if (! isset($config['db']['host'])) {
            throw new \Exception('Service configuration error. DB host is not set.');
        }
        $preparedConfig['db']['host'] = $config['db']['host'];

        if (! isset($config['db']['user'])) {
            throw new \Exception('Service configuration error. DB user is not set.');
        }
        $preparedConfig['db']['user'] = $config['db']['user'];

        if (! isset($config['db']['password'])) {
            throw new \Exception('Service configuration error. DB password is not set.');
        }
        $preparedConfig['db']['password'] = $config['db']['password'];

        if (! isset($config['db']['dbname'])) {
            throw new \Exception('Service configuration error. DB name is not set.');
        }
        $preparedConfig['db']['dbname'] = $config['db']['dbname'];

        return $preparedConfig;
    }
}