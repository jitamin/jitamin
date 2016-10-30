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

use Swift_SendmailTransport;

/**
 * PHP Mail Handler
 */
class Sendmail extends Mail
{
    /**
     * Get SwiftMailer transport
     *
     * @access protected
     * @return \Swift_Transport|\Swift_MailTransport|\Swift_SmtpTransport|\Swift_SendmailTransport
     */
    protected function getTransport()
    {
        return Swift_SendmailTransport::newInstance(MAIL_SENDMAIL_COMMAND);
    }
}
