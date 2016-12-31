<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Validator\GroupValidator;

class GroupValidatorTest extends Base
{
    public function testValidateCreation()
    {
        $groupValidator = new GroupValidator($this->container);

        $result = $groupValidator->validateCreation(['name' => 'Test']);
        $this->assertTrue($result[0]);

        $result = $groupValidator->validateCreation(['name' => '']);
        $this->assertFalse($result[0]);
    }

    public function testValidateModification()
    {
        $validator = new GroupValidator($this->container);

        $result = $validator->validateModification(['name' => 'Test', 'id' => 1]);
        $this->assertTrue($result[0]);

        $result = $validator->validateModification(['name' => 'Test']);
        $this->assertFalse($result[0]);
    }
}
