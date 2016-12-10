<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Helper;

use Hiject\Core\Base;

/**
 * Class MailHelper.
 */
class MailHelper extends Base
{
    /**
     * Get the mailbox hash from an email address.
     *
     * @param string $email
     *
     * @return string
     */
    public function getMailboxHash($email)
    {
        if (!strpos($email, '@') || !strpos($email, '+')) {
            return '';
        }

        list($localPart) = explode('@', $email);
        list(, $identifier) = explode('+', $localPart);

        return $identifier;
    }

    /**
     * Filter mail subject.
     *
     * @param string $subject
     *
     * @return string
     */
    public function filterSubject($subject)
    {
        $subject = str_replace('RE: ', '', $subject);
        $subject = str_replace('FW: ', '', $subject);

        return $subject;
    }

    /**
     * Get mail sender address.
     *
     * @return string
     */
    public function getMailSenderAddress()
    {
        $email = $this->settingModel->get('mail_sender_address');

        if (!empty($email)) {
            return $email;
        }

        return MAIL_FROM;
    }

    /**
     * Get mail sender address.
     *
     * @return string
     */
    public function getMailTransport()
    {
        $transport = $this->settingModel->get('mail_transport');

        if (!empty($transport)) {
            return $transport;
        }

        return MAIL_TRANSPORT;
    }
}
