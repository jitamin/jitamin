<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Mail;

/**
 * Mail Client Interface.
 */
interface ClientInterface
{
    /**
     * Send a HTML email.
     *
     * @param string $email
     * @param string $name
     * @param string $subject
     * @param string $html
     * @param string $author
     */
    public function sendEmail($email, $name, $subject, $html, $author);
}
