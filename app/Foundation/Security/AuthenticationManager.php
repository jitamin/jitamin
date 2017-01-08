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

use Jitamin\Bus\Event\AuthFailureEvent;
use Jitamin\Bus\Event\AuthSuccessEvent;
use Jitamin\Core\Base;
use LogicException;

/**
 * Authentication Manager.
 */
class AuthenticationManager extends Base
{
    /**
     * Event names.
     *
     * @var string
     */
    const EVENT_SUCCESS = 'auth.success';
    const EVENT_FAILURE = 'auth.failure';

    /**
     * List of authentication providers.
     *
     * @var array
     */
    private $providers = [];

    /**
     * Register a new authentication provider.
     *
     * @param AuthenticationProviderInterface $provider
     *
     * @return AuthenticationManager
     */
    public function register(AuthenticationProviderInterface $provider)
    {
        $this->providers[$provider->getName()] = $provider;

        return $this;
    }

    /**
     * Register a new authentication provider.
     *
     * @param string $name
     *
     * @return AuthenticationProviderInterface|OAuthAuthenticationProviderInterface|PasswordAuthenticationProviderInterface|PreAuthenticationProviderInterface|OAuthAuthenticationProviderInterface
     */
    public function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            throw new LogicException('Authentication provider not found: '.$name);
        }

        return $this->providers[$name];
    }

    /**
     * Execute providers that are able to validate the current session.
     *
     * @return bool
     */
    public function checkCurrentSession()
    {
        if ($this->userSession->isLogged()) {
            foreach ($this->filterProviders('SessionCheckProviderInterface') as $provider) {
                if (!$provider->isValidSession()) {
                    $this->logger->debug('Invalidate session for '.$this->userSession->getUsername());
                    $this->sessionStorage->flush();
                    $this->preAuthentication();

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Execute pre-authentication providers.
     *
     * @return bool
     */
    public function preAuthentication()
    {
        foreach ($this->filterProviders('PreAuthenticationProviderInterface') as $provider) {
            if ($provider->authenticate() && $this->userProfile->initialize($provider->getUser())) {
                $this->dispatcher->dispatch(self::EVENT_SUCCESS, new AuthSuccessEvent($provider->getName()));

                return true;
            }
        }

        return false;
    }

    /**
     * Execute username/password authentication providers.
     *
     * @param string $username
     * @param string $password
     * @param bool   $fireEvent
     *
     * @return bool
     */
    public function passwordAuthentication($username, $password, $fireEvent = true)
    {
        foreach ($this->filterProviders('PasswordAuthenticationProviderInterface') as $provider) {
            $provider->setUsername($username);
            $provider->setPassword($password);

            if ($provider->authenticate() && $this->userProfile->initialize($provider->getUser())) {
                if ($fireEvent) {
                    $this->dispatcher->dispatch(self::EVENT_SUCCESS, new AuthSuccessEvent($provider->getName()));
                }

                return true;
            }
        }

        if ($fireEvent) {
            $this->dispatcher->dispatch(self::EVENT_FAILURE, new AuthFailureEvent($username));
        }

        return false;
    }

    /**
     * Perform OAuth2 authentication.
     *
     * @param string $name
     *
     * @return bool
     */
    public function oauthAuthentication($name)
    {
        $provider = $this->getProvider($name);

        if ($provider->authenticate() && $this->userProfile->initialize($provider->getUser())) {
            $this->dispatcher->dispatch(self::EVENT_SUCCESS, new AuthSuccessEvent($provider->getName()));

            return true;
        }

        $this->dispatcher->dispatch(self::EVENT_FAILURE, new AuthFailureEvent());

        return false;
    }

    /**
     * Get the last Post-Authentication provider.
     *
     * @return PostAuthenticationProviderInterface
     */
    public function getPostAuthenticationProvider()
    {
        $providers = $this->filterProviders('PostAuthenticationProviderInterface');

        if (empty($providers)) {
            throw new LogicException('You must have at least one Post-Authentication Provider configured');
        }

        return array_pop($providers);
    }

    /**
     * Filter registered providers by interface type.
     *
     * @param string $interface
     *
     * @return array
     */
    private function filterProviders($interface)
    {
        $interface = '\Jitamin\Core\Security\\'.$interface;

        return array_filter($this->providers, function (AuthenticationProviderInterface $provider) use ($interface) {
            return is_a($provider, $interface);
        });
    }
}
