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

use Jitamin\Foundation\Middleware\BaseMiddleware;

/**
 * Class BootstrapMiddleware.
 */
class BootstrapMiddleware extends BaseMiddleware
{
    /**
     * Execute middleware.
     */
    public function execute()
    {
        $this->sessionManager->open();
        $this->dispatcher->dispatch('app.bootstrap');
        $this->sendHeaders();
        $this->next();
    }

    /**
     * Send HTTP headers.
     */
    private function sendHeaders()
    {
        $this->response->withContentSecurityPolicy($this->container['cspRules']);
        $this->response->withSecurityHeaders();

        if (ENABLE_XFRAME) {
            $this->response->withXframe();
        }

        if (ENABLE_HSTS) {
            $this->response->withStrictTransportSecurity();
        }
    }
}
