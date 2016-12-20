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

use Hiject\Validator\CommentValidator;

class CommentValidatorTest extends Base
{
    public function testValidateCreation()
    {
        $commentValidator = new CommentValidator($this->container);

        $result = $commentValidator->validateCreation(['user_id' => 1, 'task_id' => 1, 'comment' => 'bla']);
        $this->assertTrue($result[0]);

        $result = $commentValidator->validateCreation(['user_id' => 1, 'task_id' => 1, 'comment' => '']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(['user_id' => 1, 'task_id' => 'a', 'comment' => 'bla']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(['user_id' => 'b', 'task_id' => 1, 'comment' => 'bla']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(['user_id' => 1, 'comment' => 'bla']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(['task_id' => 1, 'comment' => 'bla']);
        $this->assertTrue($result[0]);

        $result = $commentValidator->validateCreation(['comment' => 'bla']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation([]);
        $this->assertFalse($result[0]);
    }

    public function testValidateModification()
    {
        $commentValidator = new CommentValidator($this->container);

        $result = $commentValidator->validateModification(['id' => 1, 'comment' => 'bla']);
        $this->assertTrue($result[0]);

        $result = $commentValidator->validateModification(['id' => 1, 'comment' => '']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateModification(['comment' => 'bla']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateModification(['id' => 'b', 'comment' => 'bla']);
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateModification([]);
        $this->assertFalse($result[0]);
    }
}
