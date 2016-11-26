<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Mail\Transport;

use Swift_SmtpTransport;

/**
 * PHP Mail Handler
 */
class Smtp extends Mail
{
    /**
     * Get SwiftMailer transport
     *
     * @access protected
     * @return \Swift_Transport|\Swift_MailTransport|\Swift_SmtpTransport|\Swift_SendmailTransport
     */
    protected function getTransport()
    {
        $transport = Swift_SmtpTransport::newInstance(MAIL_SMTP_HOSTNAME, MAIL_SMTP_PORT);
        $transport->setUsername(MAIL_SMTP_USERNAME);
        $transport->setPassword(MAIL_SMTP_PASSWORD);
        $transport->setEncryption(MAIL_SMTP_ENCRYPTION);
        if (HTTP_VERIFY_SSL_CERTIFICATE === false) {
            $transport->setStreamOptions([
                'ssl' => array[
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);
        }

        return $transport;
    }
}
