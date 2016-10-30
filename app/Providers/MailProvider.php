<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Providers;

use Hiject\Core\Mail\Client as EmailClient;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Mail Provider
 */
class MailProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['emailClient'] = function ($container) {
            $mailer = new EmailClient($container);
            $mailer->setTransport('smtp', '\Hiject\Core\Mail\Transport\Smtp');
            $mailer->setTransport('sendmail', '\Hiject\Core\Mail\Transport\Sendmail');
            $mailer->setTransport('mail', '\Hiject\Core\Mail\Transport\Mail');
            return $mailer;
        };

        return $container;
    }
}
