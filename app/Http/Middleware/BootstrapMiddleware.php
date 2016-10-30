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

use Hiject\Core\Controller\BaseMiddleware;

/**
 * Class BootstrapMiddleware
 */
class BootstrapMiddleware extends BaseMiddleware
{
    /**
     * Execute middleware
     */
    public function execute()
    {
        $this->sessionManager->open();
        $this->dispatcher->dispatch('app.bootstrap');
        $this->sendHeaders();
        $this->next();
    }

    /**
     * Send HTTP headers
     *
     * @access private
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
