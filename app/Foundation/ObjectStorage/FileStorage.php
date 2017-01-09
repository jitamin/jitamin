<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\ObjectStorage;

/**
 * Local File Storage.
 */
class FileStorage implements ObjectStorageInterface
{
    /**
     * Base path.
     *
     * @var string
     */
    private $path = '';

    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Fetch object contents.
     *
     * @param string $key
     *
     * @throws ObjectStorageException
     *
     * @return string
     */
    public function get($key)
    {
        $filename = $this->path.DIRECTORY_SEPARATOR.$key;

        if (!file_exists($filename)) {
            throw new ObjectStorageException('File not found: '.$filename);
        }

        return file_get_contents($filename);
    }

    /**
     * Save object.
     *
     * @param string $key
     * @param string $blob
     *
     * @throws ObjectStorageException
     */
    public function put($key, &$blob)
    {
        $this->createFolder($key);

        if (file_put_contents($this->path.DIRECTORY_SEPARATOR.$key, $blob) === false) {
            throw new ObjectStorageException('Unable to write the file: '.$this->path.DIRECTORY_SEPARATOR.$key);
        }
    }

    /**
     * Output directly object content.
     *
     * @param string $key
     *
     * @throws ObjectStorageException
     */
    public function output($key)
    {
        $filename = $this->path.DIRECTORY_SEPARATOR.$key;

        if (!file_exists($filename)) {
            throw new ObjectStorageException('File not found: '.$filename);
        }

        readfile($filename);
    }

    /**
     * Move local file to object storage.
     *
     * @param string $src_filename
     * @param string $key
     *
     * @throws ObjectStorageException
     *
     * @return bool
     */
    public function moveFile($src_filename, $key)
    {
        $this->createFolder($key);
        $dst_filename = $this->path.DIRECTORY_SEPARATOR.$key;

        if (!rename($src_filename, $dst_filename)) {
            throw new ObjectStorageException('Unable to move the file: '.$src_filename.' to '.$dst_filename);
        }

        return true;
    }

    /**
     * Move uploaded file to object storage.
     *
     * @param string $filename
     * @param string $key
     *
     * @return bool
     */
    public function moveUploadedFile($filename, $key)
    {
        $this->createFolder($key);

        return move_uploaded_file($filename, $this->path.DIRECTORY_SEPARATOR.$key);
    }

    /**
     * Remove object.
     *
     * @param string $key
     *
     * @return bool
     */
    public function remove($key)
    {
        $filename = $this->path.DIRECTORY_SEPARATOR.$key;

        if (file_exists($filename)) {
            return unlink($filename);
        }

        return false;
    }

    /**
     * Create object folder.
     *
     * @param string $key
     *
     * @throws ObjectStorageException
     */
    private function createFolder($key)
    {
        $folder = strpos($key, DIRECTORY_SEPARATOR) !== false ? $this->path.DIRECTORY_SEPARATOR.dirname($key) : $this->path;

        if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
            throw new ObjectStorageException('Unable to create folder: '.$folder);
        }
    }
}
