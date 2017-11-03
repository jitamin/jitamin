<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Foundation\Security\Role;
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $data = [
          [
              'username' => 'admin',
              'password' => bcrypt('admin'),
              'email'    => 'admin@admin.com',
              'role'     => Role::APP_ADMIN,
          ],
        ];

        $users = $this->table('users');
        $users->insert($data)
              ->save();
    }
}
