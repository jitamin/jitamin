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
use Hiject\Model\ProjectStarModel;

class ProjectStarTest extends Base
{
    public function testAddRemove()
    {
        $projectModel = new ProjectModel($this->container);
        $projectStarModel = new ProjectStarModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));

        $this->assertTrue($projectStarModel->addStargazer(1, 1));
        $this->assertFalse($projectStarModel->addStargazer(1, 1));

        $users = $projectStarModel->getStargazers(1);
        $this->assertCount(1, $users);
        $this->assertEquals('admin', $users[0]['username']);

        $this->assertTrue($projectStarModel->removeStargazer(1, 1));
        $this->assertFalse($projectStarModel->removeStargazer(1, 1));

        $this->assertEmpty($projectStarModel->getStargazers(1));
    }

    public function testStargazers()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectStarModel = new ProjectStarModel($this->container);

        $this->assertEquals(1, $projectModel->create('Project A'));
        $this->assertEquals(2, $projectModel->create('Project B'));

        $this->assertEquals(2, $userModel->create(['username' => 'user1', 'email' => 'user1@here']));
        $this->assertEquals(3, $userModel->create(['username' => 'user2', 'email' => 'user2@here']));
        $this->assertEquals(4, $userModel->create(['username' => 'user3', 'email' => 'user3@here']));
        $this->assertEquals(5, $userModel->create(['username' => 'user4', 'email' => 'user4@here']));

        $this->assertTrue($projectStarModel->addUser(1, 1));
        $this->assertTrue($projectStarModel->addUser(1, 2));
        $this->assertTrue($projectStarModel->addUser(1, 5));
        $this->assertTrue($projectStarModel->addUser(2, 3));
        $this->assertTrue($projectStarModel->addUser(2, 4));
        $this->assertTrue($projectStarModel->addUser(2, 5));

        $users = $projectStarModel->getStargazers(1);
        $this->assertCount(3, $users);
        $this->assertEquals('admin', $users[0]['username']);
        $this->assertEquals('user1', $users[1]['username']);
        $this->assertEquals('user4', $users[2]['username']);
    }
}
