<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\ExternalLink;

use Jitamin\Foundation\ExternalLink\ExternalLinkProviderInterface;

/**
 * Web Link Provider.
 */
class WebLinkProvider extends BaseLinkProvider implements ExternalLinkProviderInterface
{
    /**
     * Get provider name.
     *
     * @return string
     */
    public function getName()
    {
        return t('Web Link');
    }

    /**
     * Get link type.
     *
     * @return string
     */
    public function getType()
    {
        return 'weblink';
    }

    /**
     * Get a dictionary of supported dependency types by the provider.
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'related' => t('Related'),
        ];
    }

    /**
     * Return true if the provider can parse correctly the user input.
     *
     * @return bool
     */
    public function match()
    {
        $startWithHttp = strpos($this->userInput, 'http://') === 0 || strpos($this->userInput, 'https://') === 0;
        $validUrl = filter_var($this->userInput, FILTER_VALIDATE_URL);

        return $startWithHttp && $validUrl;
    }

    /**
     * Get the link found with the properties.
     *
     * @return \Jitamin\Foundation\ExternalLink\ExternalLinkInterface
     */
    public function getLink()
    {
        $link = new WebLink($this->container);
        $link->setUrl($this->userInput);

        return $link;
    }
}
