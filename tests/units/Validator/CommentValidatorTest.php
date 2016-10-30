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

        $result = $commentValidator->validateCreation(array('user_id' => 1, 'task_id' => 1, 'comment' => 'bla'));
        $this->assertTrue($result[0]);

        $result = $commentValidator->validateCreation(array('user_id' => 1, 'task_id' => 1, 'comment' => ''));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(array('user_id' => 1, 'task_id' => 'a', 'comment' => 'bla'));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(array('user_id' => 'b', 'task_id' => 1, 'comment' => 'bla'));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(array('user_id' => 1, 'comment' => 'bla'));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(array('task_id' => 1, 'comment' => 'bla'));
        $this->assertTrue($result[0]);

        $result = $commentValidator->validateCreation(array('comment' => 'bla'));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateCreation(array());
        $this->assertFalse($result[0]);
    }

    public function testValidateModification()
    {
        $commentValidator = new CommentValidator($this->container);

        $result = $commentValidator->validateModification(array('id' => 1, 'comment' => 'bla'));
        $this->assertTrue($result[0]);

        $result = $commentValidator->validateModification(array('id' => 1, 'comment' => ''));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateModification(array('comment' => 'bla'));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateModification(array('id' => 'b', 'comment' => 'bla'));
        $this->assertFalse($result[0]);

        $result = $commentValidator->validateModification(array());
        $this->assertFalse($result[0]);
    }
}
