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
 * LastLogin model.
 */
class LastLoginModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'last_logins';

    /**
     * Number of connections to keep for history.
     *
     * @var int
     */
    const NB_LOGINS = 10;

    /**
     * Create a new record.
     *
     * @param string $auth_type  Authentication method
     * @param int    $user_id    User id
     * @param string $ip         IP Address
     * @param string $user_agent User Agent
     *
     * @return bool
     */
    public function create($auth_type, $user_id, $ip, $user_agent)
    {
        $this->cleanup($user_id);

        return $this->db
            ->table(self::TABLE)
            ->insert([
                'auth_type'     => $auth_type,
                'user_id'       => $user_id,
                'ip'            => $ip,
                'user_agent'    => substr($user_agent, 0, 255),
                'date_creation' => time(),
            ]);
    }

    /**
     * Cleanup login history.
     *
     * @param int $user_id
     */
    public function cleanup($user_id)
    {
        $connections = $this->db
                            ->table(self::TABLE)
                            ->eq('user_id', $user_id)
                            ->desc('id')
                            ->findAllByColumn('id');

        if (count($connections) >= self::NB_LOGINS) {
            $this->db->table(self::TABLE)
                ->eq('user_id', $user_id)
                ->notIn('id', array_slice($connections, 0, self::NB_LOGINS - 1))
                ->remove();
        }
    }

    /**
     * Get the last connections for a given user.
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
                    ->desc('id')
                    ->columns('id', 'auth_type', 'ip', 'user_agent', 'date_creation')
                    ->findAll();
    }
}
