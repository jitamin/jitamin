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

/**
 * Class ApplicationAuthorizationMiddleware.
 */
class ApplicationAuthorizationMiddleware extends BaseMiddleware
{
    /**
     * Execute middleware.
     */
    public function execute()
    {
        if (!$this->helper->user->hasAccess($this->router->getController(), $this->router->getAction())) {
            throw new AccessForbiddenException();
        }

        $this->next();
    }
}
