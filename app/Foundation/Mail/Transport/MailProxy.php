<?php

namespace Jitamin\Foundation\Mail\Transport;

use Jitamin\Services\Mail\Transport\MailProxyTransport;

/**
 * Mail Proxy Handler.
 */
class MailProxy extends Mail
{
    /**
     * Get MailProxyTransport
     *
     * @return MailProxyTransport
     */
    protected function getTransport()
    {
        return MailProxyTransport::newInstance();
    }
}
