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

use Hiject\Core\Base;

/**
 * Base Link
 */
abstract class BaseLink extends Base
{
    /**
     * URL
     *
     * @access protected
     * @var string
     */
    protected $url = '';

    /**
     * Get link URL
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set link URL
     *
     * @access public
     * @param  string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
