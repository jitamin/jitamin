<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core;

use Jitamin\Controller\AppController;
use Jitamin\Middleware\ApplicationAuthorizationMiddleware;
use Jitamin\Middleware\AuthenticationMiddleware;
use Jitamin\Middleware\BootstrapMiddleware;
use Jitamin\Middleware\PostAuthenticationMiddleware;
use Jitamin\Middleware\ProjectAuthorizationMiddleware;
use RuntimeException;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class Application.
 */
class Application extends Base
{
    /**
     * Execute middleware and controller.
     */
    public function execute()
    {
        if ($this->runningInConsole()) {
            $this->container['dispatcher']->dispatch('app.bootstrap', new Event);
            $this->container['cli']->run();

            return;
        }

        $this->container['router']->dispatch();

        if ($this->container['router']->getController() === 'Api') {
            echo $this->container['api']->execute();

            return;
        }

        try {
            $this->executeMiddleware();

            if (!$this->response->isResponseAlreadySent()) {
                $this->executeController();
            }
        } catch (PageNotFoundException $e) {
            $controllerObject = new AppController($this->container);
            $controllerObject->notFound($e->hasLayout());
        } catch (AccessForbiddenException $e) {
            $controllerObject = new AppController($this->container);
            $controllerObject->accessForbidden($e->hasLayout(), $e->getMessage());
        }
    }

    /**
     * Determine if we are running in the console.
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli';
    }

    /**
     * Execute all middleware.
     */
    protected function executeMiddleware()
    {
        if (DEBUG) {
            $this->logger->debug(__METHOD__);
        }

        $bootstrapMiddleware = new BootstrapMiddleware($this->container);
        $authenticationMiddleware = new AuthenticationMiddleware($this->container);
        $postAuthenticationMiddleware = new PostAuthenticationMiddleware($this->container);
        $appAuthorizationMiddleware = new ApplicationAuthorizationMiddleware($this->container);
        $projectAuthorizationMiddleware = new ProjectAuthorizationMiddleware($this->container);

        $bootstrapMiddleware->setNextMiddleware($authenticationMiddleware);
        $authenticationMiddleware->setNextMiddleware($postAuthenticationMiddleware);
        $postAuthenticationMiddleware->setNextMiddleware($appAuthorizationMiddleware);
        $appAuthorizationMiddleware->setNextMiddleware($projectAuthorizationMiddleware);

        $bootstrapMiddleware->execute();
    }

    /**
     * Execute the controller.
     */
    protected function executeController()
    {
        $className = $this->getControllerClassName();

        if (DEBUG) {
            $this->logger->debug(__METHOD__.' => '.$className.'::'.$this->router->getAction());
        }

        $controllerObject = new $className($this->container);
        $controllerObject->{$this->router->getAction()}();
    }

    /**
     * Get controller class name.
     *
     * @throws RuntimeException
     *
     * @return string
     */
    protected function getControllerClassName()
    {
        if ($this->router->getPlugin() !== '') {
            $className = '\Jitamin\Plugin\\'.$this->router->getPlugin().'\Controller\\'.$this->router->getController();
        } else {
            $className = '\Jitamin\Controller\\'.str_replace('/', '\\', $this->router->getController());
        }

        if (!class_exists($className)) {
            throw new RuntimeException('Controller not found: '.$className);
        }

        if (!method_exists($className, $this->router->getAction())) {
            throw new RuntimeException('Action not implemented: '.$this->router->getAction());
        }

        return $className;
    }
}
