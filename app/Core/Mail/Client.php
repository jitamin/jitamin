<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Mail;

use Hiject\Bus\Job\EmailJob;
use Hiject\Core\Base;
use Pimple\Container;

/**
 * Mail Client.
 */
class Client extends Base
{
    /**
     * Mail transport instances.
     *
     * @var \Pimple\Container
     */
    private $transports;

    /**
     * Constructor.
     *
     * @param \Pimple\Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->transports = new Container();
    }

    /**
     * Send a HTML email.
     *
     * @param string $email
     * @param string $name
     * @param string $subject
     * @param string $html
     *
     * @return Client
     */
    public function send($email, $name, $subject, $html)
    {
        if (!empty($email)) {
            $this->queueManager->push(EmailJob::getInstance($this->container)
                ->withParams($email, $name, $subject, $html, $this->getAuthor())
            );
        }

        return $this;
    }

    /**
     * Get email author.
     *
     * @return string
     */
    public function getAuthor()
    {
        $author = 'Hiject';

        if ($this->userSession->isLogged()) {
            $author = e('%s via Hiject', $this->helper->user->getFullname());
        }

        return $author;
    }

    /**
     * Get mail transport instance.
     *
     * @param string $transport
     *
     * @return ClientInterface
     */
    public function getTransport($transport)
    {
        return $this->transports[$transport];
    }

    /**
     * Add a new mail transport.
     *
     * @param string $transport
     * @param string $class
     *
     * @return Client
     */
    public function setTransport($transport, $class)
    {
        $container = $this->container;

        $this->transports[$transport] = function () use ($class, $container) {
            return new $class($container);
        };

        return $this;
    }

    /**
     * Return the list of registered transports.
     *
     * @return array
     */
    public function getAvailableTransports()
    {
        $availableTransports = $this->transports->keys();

        return array_combine($availableTransports, $availableTransports);
    }
}
