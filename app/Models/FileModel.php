<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Exception;
use Jitamin\Core\Database\Model;
use Jitamin\Core\ObjectStorage\ObjectStorageException;
use Jitamin\Core\Thumbnail;

/**
 * Base File Model.
 */
abstract class FileModel extends Model
{
    /**
     * Get the table.
     *
     * @abstract
     *
     * @return string
     */
    abstract protected function getTable();

    /**
     * Define the foreign key.
     *
     * @abstract
     *
     * @return string
     */
    abstract protected function getForeignKey();

    /**
     * Get the path prefix.
     *
     * @abstract
     *
     * @return string
     */
    abstract protected function getPathPrefix();

    /**
     * Fire file creation event.
     *
     * @abstract
     *
     * @param int $file_id
     */
    abstract protected function fireCreationEvent($file_id);

    /**
     * Get PicoDb query to get all files.
     *
     * @return \PicoDb\Table
     */
    protected function getQuery()
    {
        return $this->db
            ->table($this->getTable())
            ->columns(
                $this->getTable().'.id',
                $this->getTable().'.name',
                $this->getTable().'.path',
                $this->getTable().'.is_image',
                $this->getTable().'.'.$this->getForeignKey(),
                $this->getTable().'.date',
                $this->getTable().'.user_id',
                $this->getTable().'.size',
                UserModel::TABLE.'.username',
                UserModel::TABLE.'.name as user_name'
            )
            ->join(UserModel::TABLE, 'id', 'user_id');
    }

    /**
     * Get a file by the id.
     *
     * @param int $file_id File id
     *
     * @return array
     */
    public function getById($file_id)
    {
        return $this->db->table($this->getTable())->eq('id', $file_id)->findOne();
    }

    /**
     * Get all files.
     *
     * @param int $id
     *
     * @return array
     */
    public function getAll($id)
    {
        return $this->getQuery()->desc($this->getTable().'.id')->eq($this->getForeignKey(), $id)->findAll();
    }

    /**
     * Get all images.
     *
     * @param int $id
     *
     * @return array
     */
    public function getAllImages($id)
    {
        return $this->getQuery()->desc($this->getTable().'.id')->eq($this->getForeignKey(), $id)->eq('is_image', 1)->findAll();
    }

    /**
     * Get all files without images.
     *
     * @param int $id
     *
     * @return array
     */
    public function getAllDocuments($id)
    {
        return $this->getQuery()->desc($this->getTable().'.id')->eq($this->getForeignKey(), $id)->eq('is_image', 0)->findAll();
    }

    /**
     * Create a file entry in the database.
     *
     * @param int    $foreign_key_id Foreign key
     * @param string $name           Filename
     * @param string $path           Path on the disk
     * @param int    $size           File size
     *
     * @return bool|int
     */
    public function create($foreign_key_id, $name, $path, $size)
    {
        $values = [
            $this->getForeignKey() => $foreign_key_id,
            'name'                 => substr($name, 0, 255),
            'path'                 => $path,
            'is_image'             => $this->isImage($name) ? 1 : 0,
            'size'                 => $size,
            'user_id'              => $this->userSession->getId() ?: 0,
            'date'                 => time(),
        ];

        $result = $this->db->table($this->getTable())->insert($values);

        if ($result) {
            $file_id = (int) $this->db->getLastId();
            $this->fireCreationEvent($file_id);

            return $file_id;
        }

        return false;
    }

    /**
     * Remove all files.
     *
     * @param int $id
     *
     * @return bool
     */
    public function removeAll($id)
    {
        $file_ids = $this->db->table($this->getTable())->eq($this->getForeignKey(), $id)->asc('id')->findAllByColumn('id');
        $results = [];

        foreach ($file_ids as $file_id) {
            $results[] = $this->remove($file_id);
        }

        return !in_array(false, $results, true);
    }

    /**
     * Remove a file.
     *
     * @param int $file_id File id
     *
     * @return bool
     */
    public function remove($file_id)
    {
        try {
            $file = $this->getById($file_id);
            $this->objectStorage->remove($file['path']);

            if ($file['is_image'] == 1) {
                $this->objectStorage->remove($this->getThumbnailPath($file['path']));
            }

            return $this->db->table($this->getTable())->eq('id', $file['id'])->remove();
        } catch (ObjectStorageException $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }

    /**
     * Check if a filename is an image (file types that can be shown as thumbnail).
     *
     * @param string $filename Filename
     *
     * @return bool
     */
    public function isImage($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpeg':
            case 'jpg':
            case 'png':
            case 'gif':
                return true;
        }

        return false;
    }

    /**
     * Generate the path for a thumbnails.
     *
     * @param string $key Storage key
     *
     * @return string
     */
    public function getThumbnailPath($key)
    {
        return 'thumbnails'.DIRECTORY_SEPARATOR.$key;
    }

    /**
     * Generate the path for a new filename.
     *
     * @param int    $id       Foreign key
     * @param string $filename Filename
     *
     * @return string
     */
    public function generatePath($id, $filename)
    {
        return $this->getPathPrefix().DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.hash('sha1', $filename.time());
    }

    /**
     * Upload multiple files.
     *
     * @param int   $id
     * @param array $files
     *
     * @return bool
     */
    public function uploadFiles($id, array $files)
    {
        try {
            if (empty($files)) {
                return false;
            }

            foreach (array_keys($files['error']) as $key) {
                $file = [
                    'name'     => $files['name'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'size'     => $files['size'][$key],
                    'error'    => $files['error'][$key],
                ];

                $this->uploadFile($id, $file);
            }

            return true;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }

    /**
     * Upload a file.
     *
     * @param int   $id
     * @param array $file
     *
     * @throws Exception
     */
    public function uploadFile($id, array $file)
    {
        if ($file['error'] == UPLOAD_ERR_OK && $file['size'] > 0) {
            $destination_filename = $this->generatePath($id, $file['name']);

            if ($this->isImage($file['name'])) {
                $this->generateThumbnailFromFile($file['tmp_name'], $destination_filename);
            }

            $this->objectStorage->moveUploadedFile($file['tmp_name'], $destination_filename);
            $this->create($id, $file['name'], $destination_filename, $file['size']);
        } else {
            throw new Exception('File not uploaded: '.var_export($file['error'], true));
        }
    }

    /**
     * Handle file upload (base64 encoded content).
     *
     * @param int    $id
     * @param string $original_filename
     * @param string $blob
     *
     * @return bool|int
     */
    public function uploadContent($id, $original_filename, $blob)
    {
        try {
            $data = base64_decode($blob);

            if (empty($data)) {
                return false;
            }

            $destination_filename = $this->generatePath($id, $original_filename);
            $this->objectStorage->put($destination_filename, $data);

            if ($this->isImage($original_filename)) {
                $this->generateThumbnailFromData($destination_filename, $data);
            }

            return $this->create(
                $id,
                $original_filename,
                $destination_filename,
                strlen($data)
            );
        } catch (ObjectStorageException $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }

    /**
     * Generate thumbnail from a blob.
     *
     * @param string $destination_filename
     * @param string $data
     */
    public function generateThumbnailFromData($destination_filename, &$data)
    {
        $blob = Thumbnail::createFromString($data)
            ->resize()
            ->toString();

        $this->objectStorage->put($this->getThumbnailPath($destination_filename), $blob);
    }

    /**
     * Generate thumbnail from a local file.
     *
     * @param string $uploaded_filename
     * @param string $destination_filename
     */
    public function generateThumbnailFromFile($uploaded_filename, $destination_filename)
    {
        $blob = Thumbnail::createFromFile($uploaded_filename)
            ->resize()
            ->toString();

        $this->objectStorage->put($this->getThumbnailPath($destination_filename), $blob);
    }
}
