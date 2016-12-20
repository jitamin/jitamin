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

use Hiject\Model\ProjectModel;
use Hiject\Validator\ProjectValidator;

class ProjectValidatorTest extends Base
{
    public function testValidateCreation()
    {
        $projectValidator = new ProjectValidator($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest1', 'identifier' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'UnitTest2']));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals('TEST1', $project['identifier']);

        $project = $projectModel->getById(2);
        $this->assertNotEmpty($project);
        $this->assertEquals('', $project['identifier']);

        $r = $projectValidator->validateCreation(['name' => 'test', 'identifier' => 'TEST1']);
        $this->assertFalse($r[0]);

        $r = $projectValidator->validateCreation(['name' => 'test', 'identifier' => 'test1']);
        $this->assertFalse($r[0]);

        $r = $projectValidator->validateCreation(['name' => 'test', 'identifier' => 'a-b-c']);
        $this->assertFalse($r[0]);

        $r = $projectValidator->validateCreation(['name' => 'test', 'identifier' => 'test 123']);
        $this->assertFalse($r[0]);
    }

    public function testValidateModification()
    {
        $projectValidator = new ProjectValidator($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest1', 'identifier' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'UnitTest2', 'identifier' => 'TEST2']));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals('TEST1', $project['identifier']);

        $project = $projectModel->getById(2);
        $this->assertNotEmpty($project);
        $this->assertEquals('TEST2', $project['identifier']);

        $r = $projectValidator->validateModification(['id' => 1, 'name' => 'test', 'identifier' => 'TEST1']);
        $this->assertTrue($r[0]);

        $r = $projectValidator->validateModification(['id' => 1, 'identifier' => 'test3']);
        $this->assertTrue($r[0]);

        $r = $projectValidator->validateModification(['id' => 1, 'identifier' => '']);
        $this->assertTrue($r[0]);

        $r = $projectValidator->validateModification(['id' => 1, 'identifier' => 'TEST2']);
        $this->assertFalse($r[0]);

        $r = $projectValidator->validateModification(['id' => 1, 'name' => '']);
        $this->assertFalse($r[0]);

        $r = $projectValidator->validateModification(['id' => 1, 'name' => null]);
        $this->assertFalse($r[0]);
    }
}
