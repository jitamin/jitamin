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

use Jitamin\Core\Database\Model;
use Jitamin\Core\Security\Token;

/**
 * Remember Me Model.
 */
class RememberMeSessionModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'remember_me';

    /**
     * Expiration (30 days).
     *
     * @var int
     */
    const EXPIRATION = 2592000;

    /**
     * Get a remember me record.
     *
     * @param $token
     * @param $sequence
     *
     * @return mixed
     */
    public function find($token, $sequence)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq('token', $token)
            ->eq('sequence', $sequence)
            ->gt('expiration', time())
            ->findOne();
    }

    /**
     * Get all sessions for a given user.
     *
     * @param int $user_id User id
     *
     * @return array
     */
    public function getAll($user_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq('user_id', $user_id)
            ->desc('date_creation')
            ->columns('id', 'ip', 'user_agent', 'date_creation', 'expiration')
            ->findAll();
    }

    /**
     * Create a new RememberMe session.
     *
     * @param int    $user_id    User id
     * @param string $ip         IP Address
     * @param string $user_agent User Agent
     *
     * @return array
     */
    public function create($user_id, $ip, $user_agent)
    {
        $token = hash('sha256', $user_id.$user_agent.$ip.Token::getToken());
        $sequence = Token::getToken();
        $expiration = time() + self::EXPIRATION;

        $this->cleanup($user_id);

        $this
            ->db
            ->table(self::TABLE)
            ->insert([
                'user_id'       => $user_id,
                'ip'            => $ip,
                'user_agent'    => $user_agent,
                'token'         => $token,
                'sequence'      => $sequence,
                'expiration'    => $expiration,
                'date_creation' => time(),
            ]);

        return [
            'token'      => $token,
            'sequence'   => $sequence,
            'expiration' => $expiration,
        ];
    }

    /**
     * Remove a session record.
     *
     * @param int $session_id Session id
     *
     * @return mixed
     */
    public function remove($session_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq('id', $session_id)
            ->remove();
    }

    /**
     * Remove old sessions for a given user.
     *
     * @param int $user_id User id
     *
     * @return bool
     */
    public function cleanup($user_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq('user_id', $user_id)
            ->lt('expiration', time())
            ->remove();
    }

    /**
     * Return a new sequence token and update the database.
     *
     * @param string $token Session token
     *
     * @return string
     */
    public function updateSequence($token)
    {
        $sequence = Token::getToken();

        $this
            ->db
            ->table(self::TABLE)
            ->eq('token', $token)
            ->update(['sequence' => $sequence]);

        return $sequence;
    }
}
