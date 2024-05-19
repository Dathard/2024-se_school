<?php

declare(strict_types=1);

namespace Core;

use App\Model\Controller\ResultInterface;

class WebapiRouter
{
    public const ROUTES_CONFIG_FILE = '/app/etc/webapi.xml';

    /**
     * @var array
     */
    private $routes;

    /**
     * @var \App\Model\Controller\Request
     */
    private $request;

    /**
     * @var \App\Model\Controller\Result\Raw
     */
    private $rawResult;

    /**
     * @var \Core\ObjectManager
     */
    private $objectManager;

    /**
     * @param \App\Model\Controller\Request $request
     * @param \App\Model\Controller\Result\Raw $rawResult
     * @param \Core\ObjectManager $objectManager
     */
    public function __construct(
        \App\Model\Controller\Request $request,
        \App\Model\Controller\Result\Raw $rawResult,
        \Core\ObjectManager $objectManager
    ) {
        $this->request = $request;
        $this->rawResult = $rawResult;
        $this->objectManager = $objectManager;
    }

    /**
     * @return \App\Model\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute(): ResultInterface
    {
        if (php_sapi_name() === 'cli' || empty($_SERVER['REQUEST_URI'])) {
            throw new \Exception('This script can only be run from a browser.');
        }

        foreach ($this->getRoutesList() as $route) {
            if ($this->request->getUri() !== $route['url']) {
                continue;
            }

            if ($this->request->getMethod() !== $route['method']) {
                continue;
            }

            $service = $this->objectManager->get($route['service']['class']);
            $response = call_user_func([$service, $route['service']['method']]);

            if (! ($response instanceof \App\Model\Controller\ResultInterface)) {
                throw new \Exception('Invalid endpoint response type.');
            }

            return $response->render();
        }

        return $this->rawResult->setContent('Page can’t be found')
            ->setHttpResponseCode(404)
            ->render();
    }

    /**
     * @return array
     */
    private function getRoutesList(): array
    {
        if (! $this->routes) {
            $this->routes = [];

            foreach (simplexml_load_file(BP . self::ROUTES_CONFIG_FILE) as $routeTag) {
                $routeConfig = [];

                foreach ($routeTag->attributes() as $name => $value) {
                    $routeConfig[$name] = $value->__toString();
                }

                foreach ($routeTag->service->attributes() as $name => $value) {
                    $routeConfig['service'][$name] = $value->__toString();
                }

                $this->routes[$routeConfig['url']] = $routeConfig;
            }
        }

        return $this->routes;
    }
}