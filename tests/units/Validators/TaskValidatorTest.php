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

use Hiject\Validator\TaskValidator;

class TaskValidatorTest extends Base
{
    public function testRequiredFields()
    {
        $taskValidator = new TaskValidator($this->container);

        $result = $taskValidator->validateCreation(['project_id' => 1, 'title' => 'test']);
        $this->assertTrue($result[0]);

        $result = $taskValidator->validateCreation(['project_id' => 1]);
        $this->assertFalse($result[0]);

        $result = $taskValidator->validateCreation(['title' => 'test']);
        $this->assertFalse($result[0]);
    }

    public function testRangeFields()
    {
        $taskValidator = new TaskValidator($this->container);

        $result = $taskValidator->validateCreation(['project_id' => 1, 'title' => 'test', 'score' => 2147483647]);
        $this->assertTrue($result[0]);

        $result = $taskValidator->validateCreation(['project_id' => 1, 'title' => 'test', 'score' => -2147483647]);
        $this->assertTrue($result[0]);

        $result = $taskValidator->validateCreation(['project_id' => 1, 'title' => 'test', 'score' => 0]);
        $this->assertTrue($result[0]);

        $result = $taskValidator->validateCreation(['project_id' => 1, 'title' => 'test', 'score' => 2147483648]);
        $this->assertFalse($result[0]);

        $result = $taskValidator->validateCreation(['project_id' => 1, 'title' => 'test', 'score' => -2147483648]);
        $this->assertFalse($result[0]);
    }
}
