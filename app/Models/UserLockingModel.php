<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Core\Database\Model;

/**
 * User Locking Model.
 */
class UserLockingModel extends Model
{
    /**
     * Get the number of failed login for the user.
     *
     * @param string $username
     *
     * @return int
     */
    public function getFailedLogin($username)
    {
        return (int) $this->db->table(UserModel::TABLE)
            ->eq('username', $username)
            ->findOneColumn('nb_failed_login');
    }

    /**
     * Reset to 0 the counter of failed login.
     *
     * @param string $username
     *
     * @return bool
     */
    public function resetFailedLogin($username)
    {
        return $this->db->table(UserModel::TABLE)
            ->eq('username', $username)
            ->update([
                'nb_failed_login'      => 0,
                'lock_expiration_date' => 0,
            ]);
    }

    /**
     * Increment failed login counter.
     *
     * @param string $username
     *
     * @return bool
     */
    public function incrementFailedLogin($username)
    {
        return $this->db->table(UserModel::TABLE)
            ->eq('username', $username)
            ->increment('nb_failed_login', 1);
    }

    /**
     * Check if the account is locked.
     *
     * @param string $username
     *
     * @return bool
     */
    public function isLocked($username)
    {
        return $this->db->table(UserModel::TABLE)
            ->eq('username', $username)
            ->neq('lock_expiration_date', 0)
            ->gte('lock_expiration_date', time())
            ->exists();
    }

    /**
     * Lock the account for the specified duration.
     *
     * @param string $username Username
     * @param int    $duration Duration in minutes
     *
     * @return bool
     */
    public function lock($username, $duration = 15)
    {
        return $this->db->table(UserModel::TABLE)
            ->eq('username', $username)
            ->update([
                'lock_expiration_date' => time() + $duration * 60,
            ]);
    }

    /**
     * Return true if the captcha must be shown.
     *
     * @param string $username
     * @param int    $tries
     *
     * @return bool
     */
    public function hasCaptcha($username, $tries = BRUTEFORCE_CAPTCHA)
    {
        return $this->getFailedLogin($username) >= $tries;
    }
}
