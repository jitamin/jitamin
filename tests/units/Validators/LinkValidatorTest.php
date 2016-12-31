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

use Jitamin\Validator\LinkValidator;

class LinkValidatorTest extends Base
{
    public function testValidateCreation()
    {
        $linkValidator = new LinkValidator($this->container);

        $r = $linkValidator->validateCreation(['label' => 'a']);
        $this->assertTrue($r[0]);

        $r = $linkValidator->validateCreation(['label' => 'a', 'opposite_label' => 'b']);
        $this->assertTrue($r[0]);

        $r = $linkValidator->validateCreation(['label' => 'relates to']);
        $this->assertFalse($r[0]);

        $r = $linkValidator->validateCreation(['label' => 'a', 'opposite_label' => 'a']);
        $this->assertFalse($r[0]);

        $r = $linkValidator->validateCreation(['label' => '']);
        $this->assertFalse($r[0]);
    }

    public function testValidateModification()
    {
        $validator = new LinkValidator($this->container);

        $r = $validator->validateModification(['id' => 20, 'label' => 'a', 'opposite_id' => 0]);
        $this->assertTrue($r[0]);

        $r = $validator->validateModification(['id' => 20, 'label' => 'a', 'opposite_id' => '1']);
        $this->assertTrue($r[0]);

        $r = $validator->validateModification(['id' => 20, 'label' => 'relates to', 'opposite_id' => '1']);
        $this->assertFalse($r[0]);

        $r = $validator->validateModification(['id' => 20, 'label' => '', 'opposite_id' => '1']);
        $this->assertFalse($r[0]);

        $r = $validator->validateModification(['label' => '', 'opposite_id' => '1']);
        $this->assertFalse($r[0]);

        $r = $validator->validateModification(['id' => 20, 'opposite_id' => '1']);
        $this->assertFalse($r[0]);

        $r = $validator->validateModification(['label' => 'test']);
        $this->assertFalse($r[0]);
    }
}
