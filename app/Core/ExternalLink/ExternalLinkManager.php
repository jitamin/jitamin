<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\ExternalLink;

use Jitamin\Core\Base;

/**
 * External Link Manager.
 */
class ExternalLinkManager extends Base
{
    /**
     * Automatic type value.
     *
     * @var string
     */
    const TYPE_AUTO = 'auto';

    /**
     * Registered providers.
     *
     * @var ExternalLinkProviderInterface[]
     */
    private $providers = [];

    /**
     * Type chosen by the user.
     *
     * @var string
     */
    private $userInputType = '';

    /**
     * Text entered by the user.
     *
     * @var string
     */
    private $userInputText = '';

    /**
     * Register a new provider.
     *
     * Providers are registered in a LIFO queue
     *
     * @param ExternalLinkProviderInterface $provider
     *
     * @return ExternalLinkManager
     */
    public function register(ExternalLinkProviderInterface $provider)
    {
        array_unshift($this->providers, $provider);

        return $this;
    }

    /**
     * Get provider.
     *
     * @param string $type
     *
     * @throws ExternalLinkProviderNotFound
     *
     * @return ExternalLinkProviderInterface
     */
    public function getProvider($type)
    {
        foreach ($this->providers as $provider) {
            if ($provider->getType() === $type) {
                return $provider;
            }
        }

        throw new ExternalLinkProviderNotFound('Unable to find link provider: '.$type);
    }

    /**
     * Get link types.
     *
     * @return array
     */
    public function getTypes()
    {
        $types = [];

        foreach ($this->providers as $provider) {
            $types[$provider->getType()] = $provider->getName();
        }

        asort($types);

        return [self::TYPE_AUTO => t('Auto')] + $types;
    }

    /**
     * Get dependency label from a provider.
     *
     * @param string $type
     * @param string $dependency
     *
     * @return string
     */
    public function getDependencyLabel($type, $dependency)
    {
        $provider = $this->getProvider($type);
        $dependencies = $provider->getDependencies();

        return isset($dependencies[$dependency]) ? $dependencies[$dependency] : $dependency;
    }

    /**
     * Find a provider that match.
     *
     * @throws ExternalLinkProviderNotFound
     *
     * @return ExternalLinkProviderInterface
     */
    public function find()
    {
        if ($this->userInputType === self::TYPE_AUTO) {
            $provider = $this->findProvider();
        } else {
            $provider = $this->getProvider($this->userInputType);
            $provider->setUserTextInput($this->userInputText);

            if (!$provider->match()) {
                throw new ExternalLinkProviderNotFound('Unable to parse URL with selected provider');
            }
        }

        if ($provider === null) {
            throw new ExternalLinkProviderNotFound('Unable to find link information from provided information');
        }

        return $provider;
    }

    /**
     * Set form values.
     *
     * @param array $values
     *
     * @return ExternalLinkManager
     */
    public function setUserInput(array $values)
    {
        $this->userInputType = empty($values['type']) ? self::TYPE_AUTO : $values['type'];
        $this->userInputText = empty($values['text']) ? '' : trim($values['text']);

        return $this;
    }

    /**
     * Set provider type.
     *
     * @param string $userInputType
     *
     * @return ExternalLinkManager
     */
    public function setUserInputType($userInputType)
    {
        $this->userInputType = $userInputType;

        return $this;
    }

    /**
     * Set external link.
     *
     * @param string $userInputText
     *
     * @return ExternalLinkManager
     */
    public function setUserInputText($userInputText)
    {
        $this->userInputText = $userInputText;

        return $this;
    }

    /**
     * Find a provider that user input.
     *
     * @return ExternalLinkProviderInterface
     */
    private function findProvider()
    {
        foreach ($this->providers as $provider) {
            $provider->setUserTextInput($this->userInputText);

            if ($provider->match()) {
                return $provider;
            }
        }
    }
}
