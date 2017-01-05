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
 * Password Reset Model.
 */
class PasswordResetModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'password_reset';

    /**
     * Token duration (30 minutes).
     *
     * @var int
     */
    const DURATION = 1800;

    /**
     * Get all tokens.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getAll($user_id)
    {
        return $this->db->table(self::TABLE)->eq('user_id', $user_id)->desc('date_creation')->limit(100)->findAll();
    }

    /**
     * Generate a new reset token for a user.
     *
     * @param int $user_id
     * @param int $expiration
     *
     * @return bool|string
     */
    public function create($user_id, $expiration = 0)
    {
        $token = $this->token->getToken();

        $result = $this->db->table(self::TABLE)->insert([
            'token'           => $token,
            'user_id'         => $user_id,
            'date_expiration' => $expiration ?: time() + self::DURATION,
            'date_creation'   => time(),
            'ip'              => $this->request->getIpAddress(),
            'user_agent'      => $this->request->getUserAgent(),
            'is_active'       => 1,
        ]);

        return $result ? $token : false;
    }

    /**
     * Get user id from the token.
     *
     * @param string $token
     *
     * @return int
     */
    public function getUserIdByToken($token)
    {
        return $this->db->table(self::TABLE)->eq('token', $token)->eq('is_active', 1)->gte('date_expiration', time())->findOneColumn('user_id');
    }

    /**
     * Disable all tokens for a user.
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function disable($user_id)
    {
        return $this->db->table(self::TABLE)->eq('user_id', $user_id)->update(['is_active' => 0]);
    }
}
