<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\ExternalLink;

use Hiject\Core\ExternalLink\ExternalLinkProviderInterface;

/**
 * File Link Provider.
 */
class FileLinkProvider extends BaseLinkProvider implements ExternalLinkProviderInterface
{
    protected $excludedPrefixes = [
        'http',
        'ftp',
    ];

    /**
     * Get provider name.
     *
     * @return string
     */
    public function getName()
    {
        return t('Local File');
    }

    /**
     * Get link type.
     *
     * @return string
     */
    public function getType()
    {
        return 'file';
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
        if (strpos($this->userInput, '://') === false) {
            return false;
        }

        foreach ($this->excludedPrefixes as $prefix) {
            if (strpos($this->userInput, $prefix) === 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the link found with the properties.
     *
     * @return \Hiject\Core\ExternalLink\ExternalLinkInterface
     */
    public function getLink()
    {
        $link = new FileLink($this->container);
        $link->setUrl($this->userInput);

        return $link;
    }
}
