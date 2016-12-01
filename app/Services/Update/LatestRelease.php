<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Services\Update;

use Httpful\Request;
use Hiject\Core\Base;
use Hiject\Core\Cache\FileCache;

/**
 * A class to get the latest release tag for Github.
 */
class LatestRelease extends Base 
{
     const CACHE_TIME_IN_HOURS = 1;

     /**
     * @var string
     */
    private $github_url = 'https://api.github.com/repos/hiject/hiject/releases/latest';

    /**
     * Get the latest release from Github.
     *
     * @return string
     */
    public function latest()
    {
        $cache_for = self::CACHE_TIME_IN_HOURS * 60;

        if ($release = $this->container['cacheDriver']->get('hiject_latest_version')) {
            return $release;
        } else {
            $body = $this->container['httpClient']->getJson($this->github_url, ['Accept: application/vnd.github.v3+json']);

            if (is_array($body)) {
                $release = $body['tag_name'];
                $this->container['cacheDriver']->set('hiject_latest_version', $release, $cache_for);
                return $release;
            }
        }


        return false;
    }
}
