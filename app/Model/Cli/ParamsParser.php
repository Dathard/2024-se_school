<?php

declare(strict_types=1);

namespace App\Model\Cli;

class ParamsParser
{
    /**
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function execute(array $params): array
    {
        $result = [];

        foreach ($params as $param) {
            if (preg_match('/^--'. AbstractCommand::CLI_PARAMETER_HELP.'$/', $param)) {
                $result[AbstractCommand::CLI_PARAMETER_HELP] = true;
                continue;
            }

            if (! preg_match('/^--([a-z\-_]*)=(.*)$/', $param, $matches)) {
                throw new \Exception('Invalid param: ' . $param);
            }

            $result[$matches[1]] = $matches[2];
        }

        return $result;
    }
}