<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Middleware;

use Jitamin\Foundation\Exceptions\AccessForbiddenException;
use Jitamin\Foundation\Middleware\BaseMiddleware;

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
        if (!$this->helper->user->hasAccess($this->router->getController(), $this->router->getAction(), $this->router->getPlugin())) {
            throw new AccessForbiddenException();
        }

        $this->next();
    }
}
