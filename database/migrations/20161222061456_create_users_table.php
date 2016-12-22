<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('username', 'string', ['limit'=>50])
              ->addColumn('password', 'string', ['null' => true])
              ->addColumn('is_ldap_user', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('name', 'string', ['null' => true])
              ->addColumn('email', 'string')
              ->addColumn('google_id', 'string', ['null'=> true, 'limit' => 30])
              ->addColumn('github_id', 'string', ['null' => true, 'limit' => 30])
              ->addColumn('notifications_enabled', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('timezone', 'string', ['null' => true, 'limit' => 50])
              ->addColumn('language', 'string', ['null' => true, 'limit' => 5])
              ->addColumn('disable_login_form', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('twofactor_activated', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('twofactor_secret', 'string', ['null' => true, 'limit' => 16])
              ->addColumn('token', 'string', ['null'=> true, 'default' => ''])
              ->addColumn('notifications_filter', 'integer', ['null' => true, 'default' => 4])
              ->addColumn('nb_failed_login', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('lock_expiration_date', 'biginteger', ['null' => true])
              ->addColumn('gitlab_id', 'integer', ['null' => true])
              ->addColumn('role', 'string', ['limit' => 25, 'default' => 'app-user'])
              ->addColumn('is_active', 'boolean', ['null' => true, 'default' => true])
              ->addColumn('avatar_path', 'string', ['null' => true])
              ->addColumn('skin', 'string', ['null' => true, 'limit'=>15])
              ->addIndex(['username'], ['unique' => true])
              ->addIndex(['email'], ['unique' => true])
              ->create();
    }
}
