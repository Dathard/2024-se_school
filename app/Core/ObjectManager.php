<?php

namespace App\Core;

class ObjectManager
{
    /**
     * @var array
     */
    private $sharedInstances;

    /**
     * @param array $sharedInstances
     */
    public function __construct(&$sharedInstances = [])
    {
        $this->sharedInstances = &$sharedInstances;
        $this->sharedInstances[__CLASS__] = $this;
    }

    /**
     * @param $type
     * @return bool|Object
     */
    public function create(string $type)
    {
        try {
            $reflection = new \ReflectionClass($type);

            $constructor = $reflection->getConstructor();

            $params = [];
            if ($constructor) {
                foreach ($constructor->getParameters() as $parameter) {
                    $params[$parameter->getName()] = [
                        'class' => $parameter->getClass() ? $parameter->getClass()->getName() : false,
                        'value' => $parameter->isOptional() ? $parameter->getDefaultValue() : false,
                    ];
                }

                $params = $this->resolveArguments($params);
            }
        } catch (\ReflectionException $e) {
            return false;
        }

        if ($reflection->hasMethod('getInstance')) {
            return $type::getInstance($params);
        }

        return new $type(...$params);
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function get(string $type)
    {
        if (! isset($this->sharedInstances[$type])) {
            $this->sharedInstances[$type] = $this->create($type);
        }

        return $this->sharedInstances[$type];
    }

    /**
     * @param array $params
     * @return array
     */
    private function resolveArguments(array $params): array
    {
        $result = [];
        foreach ($params as $param) {
            if ($param['class']) {
                $result[] = $this->get($param['class']);
            } else {
                $result[] = $param['value'];
            }
        }

        return $result;
    }
}