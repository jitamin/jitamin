<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\ObjectStorage;

/**
 * Object Storage Interface.
 */
interface ObjectStorageInterface
{
    /**
     * Fetch object contents.
     *
     * @param string $key
     *
     * @return string
     */
    public function get($key);

    /**
     * Save object.
     *
     * @param string $key
     * @param string $blob
     */
    public function put($key, &$blob);

    /**
     * Output directly object content.
     *
     * @param string $key
     */
    public function output($key);

    /**
     * Move local file to object storage.
     *
     * @param string $filename
     * @param string $key
     *
     * @return bool
     */
    public function moveFile($filename, $key);

    /**
     * Move uploaded file to object storage.
     *
     * @param string $filename
     * @param string $key
     *
     * @return bool
     */
    public function moveUploadedFile($filename, $key);

    /**
     * Remove object.
     *
     * @param string $key
     *
     * @return bool
     */
    public function remove($key);
}
