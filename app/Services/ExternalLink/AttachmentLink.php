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
 * Attachment Link.
 */
class AttachmentLink extends BaseLink implements ExternalLinkInterface
{
    /**
     * Get link title.
     *
     * @return string
     */
    public function getTitle()
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        return basename($path);
    }
}
