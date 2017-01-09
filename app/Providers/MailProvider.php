<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Foundation\Mail\Client as EmailClient;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Mail Provider.
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
            $mailer->setTransport('smtp', '\Jitamin\Foundation\Mail\Transport\Smtp');
            $mailer->setTransport('sendmail', '\Jitamin\Foundation\Mail\Transport\Sendmail');
            $mailer->setTransport('mail', '\Jitamin\Foundation\Mail\Transport\Mail');

            return $mailer;
        };

        return $container;
    }
}
