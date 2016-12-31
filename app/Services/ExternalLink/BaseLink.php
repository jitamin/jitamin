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

use Jitamin\Core\Base;

/**
 * Base Link.
 */
abstract class BaseLink extends Base
{
    /**
     * URL.
     *
     * @var string
     */
    protected $url = '';

    /**
     * Get link URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set link URL.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
