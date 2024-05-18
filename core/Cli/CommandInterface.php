<?php

namespace Core\Cli;

interface CommandInterface
{
    public const CLI_PARAMETER_HELP = 'help';

    /**
     * @param array $params
     * @return mixed
     */
    public function execute(array $params);
}