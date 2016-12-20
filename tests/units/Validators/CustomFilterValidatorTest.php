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

use Hiject\Validator\CustomFilterValidator;

class CustomFilterValidatorTest extends Base
{
    public function testValidateCreation()
    {
        $customFilterValidator = new CustomFilterValidator($this->container);

        // Validate creation
        $r = $customFilterValidator->validateCreation(['filter' => 'test', 'name' => 'test', 'user_id' => 1, 'project_id' => 1, 'is_shared' => 0]);
        $this->assertTrue($r[0]);

        $r = $customFilterValidator->validateCreation(['filter' => str_repeat('a', 101), 'name' => 'test', 'user_id' => 1, 'project_id' => 1, 'is_shared' => 0]);
        $this->assertFalse($r[0]);

        $r = $customFilterValidator->validateCreation(['name' => 'test', 'user_id' => 1, 'project_id' => 1, 'is_shared' => 0]);
        $this->assertFalse($r[0]);
    }

    public function testValidateModification()
    {
        $validator = new CustomFilterValidator($this->container);

        $r = $validator->validateModification(['id' => 1, 'filter' => 'test', 'name' => 'test', 'user_id' => 1, 'project_id' => 1, 'is_shared' => 0]);
        $this->assertTrue($r[0]);

        $r = $validator->validateModification(['filter' => 'test', 'name' => 'test', 'user_id' => 1, 'project_id' => 1, 'is_shared' => 0]);
        $this->assertFalse($r[0]);

        $r = $validator->validateModification(['id' => 1, 'filter' => str_repeat('a', 101), 'name' => 'test', 'user_id' => 1, 'project_id' => 1, 'is_shared' => 0]);
        $this->assertFalse($r[0]);

        $r = $validator->validateModification(['id' => 1, 'name' => 'test', 'user_id' => 1, 'project_id' => 1, 'is_shared' => 0]);
        $this->assertFalse($r[0]);
    }
}
