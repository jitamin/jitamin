<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Job;

/**
 * Async HTTP Client (fire and forget)
 */
class HttpAsyncJob extends BaseJob
{
    /**
     * Set job parameters
     *
     * @access public
     * @param string $method
     * @param string $url
     * @param string $content
     * @param array  $headers
     * @return $this
     */
    public function withParams($method, $url, $content, array $headers)
    {
        $this->jobParams = [$method, $url, $content, $headers];
        return $this;
    }

    /**
     * Set job parameters
     *
     * @access public
     * @param string $method
     * @param string $url
     * @param string $content
     * @param array  $headers
     * @return $this
     */
    public function execute($method, $url, $content, array $headers)
    {
        $this->httpClient->doRequest($method, $url, $content, $headers);
    }
}
