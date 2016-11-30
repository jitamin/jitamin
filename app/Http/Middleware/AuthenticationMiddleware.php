<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Middleware;

use Hiject\Core\Controller\AccessForbiddenException;
use Hiject\Core\Controller\BaseMiddleware;
use Hiject\Core\Security\Role;

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

    protected function handleAuthentication()
    {
        if (!$this->userSession->isLogged() && !$this->authenticationManager->preAuthentication()) {
            $this->nextMiddleware = null;

            if ($this->request->isAjax()) {
                $this->response->text('Not Authorized', 401);
            } else {
                $this->sessionStorage->redirectAfterLogin = $this->request->getUri();
                $this->response->redirect($this->helper->url->to('AuthController', 'login'));
            }
        }
    }

    protected function isPublicAccess()
    {
        if ($this->applicationAuthorization->isAllowed($this->router->getController(), $this->router->getAction(), Role::APP_PUBLIC)) {
            $this->nextMiddleware = null;

            return true;
        }

        return false;
    }
}
