<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Middleware;

use Jitamin\Core\Controller\AccessForbiddenException;
use Jitamin\Core\Controller\BaseMiddleware;
use Jitamin\Core\Security\Role;

/**
 * Class AuthenticationMiddleware.
 */
class AuthenticationMiddleware extends BaseMiddleware
{
    /**
     * Execute middleware.
     */
    public function execute()
    {
        if (!$this->authenticationManager->checkCurrentSession()) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        if (!$this->isPublicAccess()) {
            $this->handleAuthentication();
        }

        $this->next();
    }

    /**
     * Handle authentication.
     */
    protected function handleAuthentication()
    {
        if (!$this->userSession->isLogged() && !$this->authenticationManager->preAuthentication()) {
            $this->nextMiddleware = null;

            if ($this->request->isAjax()) {
                $this->response->text('Not Authorized', 401);
            } else {
                $this->sessionStorage->redirectAfterLogin = $this->request->getUri();
                $this->response->redirect($this->helper->url->to('Auth/AuthController', 'login'));
            }
        }
    }

    /**
     * Check authentication.
     */
    protected function isPublicAccess()
    {
        if ($this->applicationAuthorization->isAllowed($this->router->getController(), $this->router->getAction(), Role::APP_PUBLIC, $this->router->getPlugin())) {
            $this->nextMiddleware = null;

            return true;
        }

        return false;
    }
}
