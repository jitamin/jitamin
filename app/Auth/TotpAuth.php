<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Auth;

use Jitamin\Foundation\Base;
use Jitamin\Foundation\Security\PostAuthenticationProviderInterface;
use Otp\GoogleAuthenticator;
use Otp\Otp;
use ParagonIE\ConstantTime\Base32;

/**
 * TOTP Authentication Provider.
 */
class TotpAuth extends Base implements PostAuthenticationProviderInterface
{
    /**
     * User pin code.
     *
     * @var string
     */
    protected $code = '';

    /**
     * Private key.
     *
     * @var string
     */
    protected $secret = '';

    /**
     * Get authentication provider name.
     *
     * @return string
     */
    public function getName()
    {
        return t('Time-based One-time Password Algorithm');
    }

    /**
     * Authenticate the user.
     *
     * @return bool
     */
    public function authenticate()
    {
        $otp = new Otp();

        return $otp->checkTotp(Base32::decode($this->secret), $this->code);
    }

    /**
     * Called before to prompt the user.
     */
    public function beforeCode()
    {
    }

    /**
     * Set validation code.
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Generate secret.
     *
     * @return string
     */
    public function generateSecret()
    {
        $this->secret = GoogleAuthenticator::generateRandom();

        return $this->secret;
    }

    /**
     * Set secret token.
     *
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get secret token.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Get QR code url.
     *
     * @param string $label
     *
     * @return string
     */
    public function getQrCodeUrl($label)
    {
        if (empty($this->secret)) {
            return '';
        }

        $options = ['issuer' => TOTP_ISSUER];

        return GoogleAuthenticator::getQrCodeUrl('totp', $label, $this->secret, null, $options);
    }

    /**
     * Get key url (empty if no url can be provided).
     *
     * @param string $label
     *
     * @return string
     */
    public function getKeyUrl($label)
    {
        if (empty($this->secret)) {
            return '';
        }

        $options = ['issuer' => TOTP_ISSUER];

        return GoogleAuthenticator::getKeyUri('totp', $label, $this->secret, null, $options);
    }
}
