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

/**
 * Class ApplicationAuthorizationMiddleware
 */
class ApplicationAuthorizationMiddleware extends BaseMiddleware
{
    /**
     * Execute middleware
     */
    public function execute()
    {
        if (! $this->helper->user->hasAccess($this->router->getController(), $this->router->getAction())) {
            throw new AccessForbiddenException();
        }

        $this->next();
    }
}
