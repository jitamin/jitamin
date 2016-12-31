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

use Jitamin\Model\ProjectModel;
use Jitamin\Model\ProjectStarModel;
use Jitamin\Model\UserModel;

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

        $this->assertEquals(1, $projectModel->create(['name' => 'Project A']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project B']));

        $this->assertEquals(2, $userModel->create(['username' => 'user1', 'email' => 'user1@here']));
        $this->assertEquals(3, $userModel->create(['username' => 'user2', 'email' => 'user2@here']));
        $this->assertEquals(4, $userModel->create(['username' => 'user3', 'email' => 'user3@here']));
        $this->assertEquals(5, $userModel->create(['username' => 'user4', 'email' => 'user4@here']));

        $this->assertTrue($projectStarModel->addStargazer(1, 1));
        $this->assertTrue($projectStarModel->addStargazer(1, 2));
        $this->assertTrue($projectStarModel->addStargazer(1, 5));
        $this->assertTrue($projectStarModel->addStargazer(2, 3));
        $this->assertTrue($projectStarModel->addStargazer(2, 4));
        $this->assertTrue($projectStarModel->addStargazer(2, 5));

        $users = $projectStarModel->getStargazers(1);
        $this->assertCount(3, $users);
        $this->assertEquals('admin', $users[0]['username']);
        $this->assertEquals('user1', $users[1]['username']);
        $this->assertEquals('user4', $users[2]['username']);

        $users = $projectStarModel->getStargazers(2);
        $this->assertCount(3, $users);
        $this->assertEquals('user2', $users[0]['username']);
        $this->assertEquals('user3', $users[1]['username']);
        $this->assertEquals('user4', $users[2]['username']);

        $projects = $projectStarModel->getProjects(1);
        $this->assertCount(1, $projects);
        $this->assertEquals(1, $projects[0]['id']);
        $this->assertEquals('Project A', $projects[0]['name']);

        $projects = $projectStarModel->getProjects(2);
        $this->assertCount(1, $projects);
        $this->assertEquals(1, $projects[0]['id']);
        $this->assertEquals('Project A', $projects[0]['name']);

        $projects = $projectStarModel->getProjects(3);
        $this->assertCount(1, $projects);
        $this->assertEquals(2, $projects[0]['id']);
        $this->assertEquals('Project B', $projects[0]['name']);

        $projects = $projectStarModel->getProjects(4);
        $this->assertCount(1, $projects);
        $this->assertEquals(2, $projects[0]['id']);
        $this->assertEquals('Project B', $projects[0]['name']);

        $projects = $projectStarModel->getProjects(5);
        $this->assertCount(2, $projects);
        $this->assertEquals(1, $projects[0]['id']);
        $this->assertEquals('Project A', $projects[0]['name']);
        $this->assertEquals(2, $projects[1]['id']);
        $this->assertEquals('Project B', $projects[1]['name']);

        $projects = $projectStarModel->getProjects(6);
        $this->assertCount(0, $projects);
    }
}
