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

use Hiject\Core\ExternalLink\ExternalLinkInterface;

/**
 * File Link.
 */
class FileLink extends BaseLink implements ExternalLinkInterface
{
    /**
     * Get link title.
     *
     * @return string
     */
    public function getTitle()
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        return basename(str_replace('\\', '/', $path));
    }
}
