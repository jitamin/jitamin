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

use Jitamin\Core\ExternalLink\ExternalLinkInterface;

/**
 * Web Link.
 */
class WebLink extends BaseLink implements ExternalLinkInterface
{
    /**
     * Get link title.
     *
     * @return string
     */
    public function getTitle()
    {
        $html = $this->httpClient->get($this->url);

        if (preg_match('/<title>(.*)<\/title>/siU', $html, $matches)) {
            return trim($matches[1]);
        }

        $components = parse_url($this->url);

        if (!empty($components['host']) && !empty($components['path'])) {
            return $components['host'].$components['path'];
        }

        return t('Title not found');
    }
}
