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

/**
 * Avatar File.
 */
class AvatarFileModel extends Model
{
    /**
     * Path prefix.
     *
     * @var string
     */
    const PATH_PREFIX = 'avatars';

    /**
     * Get image filename.
     *
     * @param int $user_id
     *
     * @return string
     */
    public function getFilename($user_id)
    {
        return $this->db->table(UserModel::TABLE)->eq('id', $user_id)->findOneColumn('avatar_path');
    }

    /**
     * Add avatar in the user profile.
     *
     * @param int    $user_id Foreign key
     * @param string $path    Path on the disk
     *
     * @return bool
     */
    public function create($user_id, $path)
    {
        $result = $this->db->table(UserModel::TABLE)->eq('id', $user_id)->update([
            'avatar_path' => $path,
        ]);

        $this->userSession->refresh($user_id);

        return $result;
    }

    /**
     * Remove avatar from the user profile.
     *
     * @param int $user_id Foreign key
     *
     * @return bool
     */
    public function remove($user_id)
    {
        try {
            $filename = $this->getFilename($user_id);

            if (!empty($filename)) {
                $this->objectStorage->remove($filename);

                return $this->db->table(UserModel::TABLE)->eq('id', $user_id)->update(['avatar_path' => '']);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Upload avatar image file.
     *
     * @param int   $user_id
     * @param array $file
     *
     * @return bool
     */
    public function uploadImageFile($user_id, array $file)
    {
        try {
            if ($file['error'] == UPLOAD_ERR_OK && $file['size'] > 0) {
                $destinationFilename = $this->generatePath($user_id, $file['name']);
                $this->objectStorage->moveUploadedFile($file['tmp_name'], $destinationFilename);
                $this->create($user_id, $destinationFilename);
            } else {
                throw new Exception('File not uploaded: '.var_export($file['error'], true));
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Upload avatar image content.
     *
     * @param int    $user_id
     * @param string $blob
     *
     * @return bool
     */
    public function uploadImageContent($user_id, &$blob)
    {
        try {
            $destinationFilename = $this->generatePath($user_id, 'imageContent');
            $this->objectStorage->put($destinationFilename, $blob);
            $this->create($user_id, $destinationFilename);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Generate the path for a new filename.
     *
     * @param int    $user_id
     * @param string $filename
     *
     * @return string
     */
    public function generatePath($user_id, $filename)
    {
        return implode(DIRECTORY_SEPARATOR, [self::PATH_PREFIX, $user_id, hash('sha1', $filename.time())]);
    }
}
