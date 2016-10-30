<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Security;

/**
 * Post Authentication Provider Interface
 */
interface PostAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Called only one time before to prompt the user for pin code
     *
     * @access public
     */
    public function beforeCode();

    /**
     * Set user pin-code
     *
     * @access public
     * @param  string $code
     */
    public function setCode($code);

    /**
     * Generate secret if necessary
     *
     * @access public
     * @return string
     */
    public function generateSecret();

    /**
     * Set secret token (fetched from user profile)
     *
     * @access public
     * @param  string  $secret
     */
    public function setSecret($secret);

    /**
     * Get secret token (will be saved in user profile)
     *
     * @access public
     * @return string
     */
    public function getSecret();

    /**
     * Get QR code url (empty if no QR can be provided)
     *
     * @access public
     * @param  string $label
     * @return string
     */
    public function getQrCodeUrl($label);

    /**
     * Get key url (empty if no url can be provided)
     *
     * @access public
     * @param  string $label
     * @return string
     */
    public function getKeyUrl($label);
}
