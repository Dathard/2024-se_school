<?php

declare(strict_types=1);

namespace Core\Cli;

class CommandList
{
    public const CONFIG_FILE = '/core/cli-commands.xml';

    private $commands;

    /**
     * @return array
     */
    public function get(): array
    {
        if (! $this->commands) {
            foreach (simplexml_load_file(BP . self::CONFIG_FILE) as $commandTag) {
                $commandConfig = $this->getAttributes($commandTag);

                if ($commandTag->params) {
                    $commandConfig['params'] = [];
                    foreach ($commandTag->params->children() as $param) {
                        $commandConfig['params'][] = $this->getAttributes($param);
                    }
                }

                $this->commands[$commandConfig['name']] = $commandConfig;
            }
        }

        return $this->commands;
    }

    /**
     * @param $tag
     * @return array
     */
    private function getAttributes($tag): array
    {
        $attributes = [];

        foreach($tag->attributes() as $name => $value) {
            $attributes[$name] = $value->__toString();
        }

        return $attributes;
    }
}