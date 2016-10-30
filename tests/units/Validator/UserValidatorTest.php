<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Hiject\Validator\UserValidator;
use Hiject\Core\Security\Role;

class UserValidatorTest extends Base
{
    public function testValidatePasswordModification()
    {
        $userValidator = new UserValidator($this->container);

        $this->container['sessionStorage']->user = array(
            'id' => 1,
            'role' => Role::APP_ADMIN,
            'username' => 'admin',
        );

        $result = $userValidator->validatePasswordModification(array());
        $this->assertFalse($result[0]);

        $result = $userValidator->validatePasswordModification(array('id' => 1));
        $this->assertFalse($result[0]);

        $result = $userValidator->validatePasswordModification(array('id' => 1, 'password' => '123456'));
        $this->assertFalse($result[0]);

        $result = $userValidator->validatePasswordModification(array('id' => 1, 'password' => '123456', 'confirmation' => 'wrong'));
        $this->assertFalse($result[0]);

        $result = $userValidator->validatePasswordModification(array('id' => 1, 'password' => '123456', 'confirmation' => '123456'));
        $this->assertFalse($result[0]);

        $result = $userValidator->validatePasswordModification(array('id' => 1, 'password' => '123456', 'confirmation' => '123456', 'current_password' => 'wrong'));
        $this->assertFalse($result[0]);

        $result = $userValidator->validatePasswordModification(array('id' => 1, 'password' => '123456', 'confirmation' => '123456', 'current_password' => 'admin'));
        $this->assertTrue($result[0]);
    }
}
