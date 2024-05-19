<?php

declare(strict_types=1);

namespace Core\Cli;

class Runner
{
    /**
     * @var \Core\Cli\CommandList
     */
    private $commandList;

    /**
     * @var \Core\Cli\CommandParamsParser
     */
    private $paramsParser;

    /**
     * @var \Core\ObjectManager
     */
    private $objectManager;

    /**
     * @param \Core\Cli\CommandList $commandList
     * @param \Core\Cli\CommandParamsParser $paramsParser
     * @param \Core\ObjectManager $objectManager
     */
    public function __construct(
        \Core\Cli\CommandList $commandList,
        \Core\Cli\CommandParamsParser $paramsParser,
        \Core\ObjectManager $objectManager
    ) {
        $this->commandList = $commandList;
        $this->paramsParser = $paramsParser;
        $this->objectManager = $objectManager;
    }

    public function run($commandName, $params = []): void
    {
        $commandList = $this->commandList->get();

        if (! array_key_exists($commandName, $commandList)) {
            throw new \Exception(
                'The command is not valid. Please check it and try again.' .
                "\nList of allowed commands: " . print_r($commandList, true)
            );
        }

        $params = $this->paramsParser->execute($params);
        if (in_array(CommandInterface::CLI_PARAMETER_HELP, $params)) {
            print_r($commandList);
            exit(0);
        }

        if (array_key_exists('params', $commandList[$commandName])) {
            foreach ($commandList[$commandName]['params'] as $param) {
                if (array_key_exists($param['name'], $params)) {
                    continue;
                }

                throw new \Exception(
                    "The parameter '{$param['name']}' was not set." . PHP_EOL .
                    "More details about the required parameters: " . PHP_EOL .
                    print_r($commandList[$commandName], true)
                );
            }
        }

        /** @var \Core\Cli\CommandInterface $command */
        $command = $this->objectManager->get($commandList[$commandName]['class']);
        $command->execute($params);
    }
}