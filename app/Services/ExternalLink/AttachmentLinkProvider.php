<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\ExternalLink;

use Jitamin\Core\ExternalLink\ExternalLinkProviderInterface;

/**
 * Attachment Link Provider.
 */
class AttachmentLinkProvider extends BaseLinkProvider implements ExternalLinkProviderInterface
{
    /**
     * File extensions that are not attachments.
     *
     * @var array
     */
    protected $extensions = [
        'html',
        'htm',
        'xhtml',
        'php',
        'jsp',
        'do',
        'action',
        'asp',
        'aspx',
        'cgi',
    ];

    /**
     * Get provider name.
     *
     * @return string
     */
    public function getName()
    {
        return t('Attachment');
    }

    /**
     * Get link type.
     *
     * @return string
     */
    public function getType()
    {
        return 'attachment';
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
        if (preg_match('/^https?:\/\/.*\.([^\/]+)$/', $this->userInput, $matches)) {
            return $this->isValidExtension($matches[1]);
        }

        return false;
    }

    /**
     * Get the link found with the properties.
     *
     * @return \Jitamin\Core\ExternalLink\ExternalLinkInterface
     */
    public function getLink()
    {
        $link = new AttachmentLink($this->container);
        $link->setUrl($this->userInput);

        return $link;
    }

    /**
     * Check file extension.
     *
     * @param string $extension
     *
     * @return bool
     */
    protected function isValidExtension($extension)
    {
        $extension = strtolower($extension);

        foreach ($this->extensions as $ext) {
            if ($extension === $ext) {
                return false;
            }
        }

        return true;
    }
}
