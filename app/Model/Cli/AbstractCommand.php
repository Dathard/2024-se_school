<?php

declare(strict_types=1);

namespace App\Model\Cli;

abstract class AbstractCommand
{
    public const CLI_PARAMETER_HELP = 'help';

    /**
     * @param array $params
     * @return mixed
     */
    abstract public function execute(array $params);

    abstract public function help();
}