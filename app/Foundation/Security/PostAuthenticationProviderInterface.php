<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Security;

/**
 * Post Authentication Provider Interface.
 */
interface PostAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Called only one time before to prompt the user for pin code.
     */
    public function beforeCode();

    /**
     * Set user pin-code.
     *
     * @param string $code
     */
    public function setCode($code);

    /**
     * Generate secret if necessary.
     *
     * @return string
     */
    public function generateSecret();

    /**
     * Set secret token (fetched from user profile).
     *
     * @param string $secret
     */
    public function setSecret($secret);

    /**
     * Get secret token (will be saved in user profile).
     *
     * @return string
     */
    public function getSecret();

    /**
     * Get QR code url (empty if no QR can be provided).
     *
     * @param string $label
     *
     * @return string
     */
    public function getQrCodeUrl($label);

    /**
     * Get key url (empty if no url can be provided).
     *
     * @param string $label
     *
     * @return string
     */
    public function getKeyUrl($label);
}
