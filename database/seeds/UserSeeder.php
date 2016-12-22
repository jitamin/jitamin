<?php

use Jitamin\Core\Security\Role;
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
              'username'    => 'admin',
              'password' => bcrypt('admin'),
              'email' => 'admin@admin.com',
              'role' => Role::APP_ADMIN,
          ],
        ];

        $users = $this->table('users');
        $users->insert($data)
              ->save();
    }
}
