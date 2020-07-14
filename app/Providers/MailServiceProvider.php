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
 * Class of Mail Service Provider.
 */
class MailServiceProvider implements ServiceProviderInterface
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
            $mailer->setTransport('mailproxy', '\Jitamin\Foundation\Mail\Transport\MailProxy');
            $mailer->setTransport('smtp', '\Jitamin\Foundation\Mail\Transport\Smtp');
            $mailer->setTransport('sendmail', '\Jitamin\Foundation\Mail\Transport\Sendmail');
            $mailer->setTransport('mail', '\Jitamin\Foundation\Mail\Transport\Mail');

            return $mailer;
        };

        return $container;
    }
}
