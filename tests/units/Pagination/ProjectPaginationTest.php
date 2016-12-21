<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Core\Security\Role;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\ProjectUserRoleModel;
use Jitamin\Model\UserModel;
use Jitamin\Pagination\ProjectPagination;

require_once __DIR__.'/../Base.php';

class ProjectPaginationTest extends Base
{
    public function testDashboardPagination()
    {
        $projectModel = new ProjectModel($this->container);
        $projectUserRoleModel = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);
        $projectPagination = new ProjectPagination($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project #2', 'is_private' => 1]));
        $this->assertEquals(3, $projectModel->create(['name' => 'Project #3']));
        $this->assertEquals(4, $projectModel->create(['name' => 'Project #4', 'is_private' => 1]));

        $this->assertEquals(2, $userModel->create(['username' => 'test', 'email' => 'test@test']));
        $this->assertTrue($projectUserRoleModel->addUser(1, 2, Role::PROJECT_MANAGER));
        $this->assertTrue($projectUserRoleModel->addUser(2, 2, Role::PROJECT_MANAGER));

        $this->assertCount(2, $projectPagination->getDashboardPaginator(2, 'projects', 5)->getCollection());
        $this->assertCount(0, $projectPagination->getDashboardPaginator(3, 'projects', 5)->getCollection());
        $this->assertCount(2, $projectPagination->getDashboardPaginator(2, 'projects', 5)->setOrder(ProjectModel::TABLE.'.id')->getCollection());
        $this->assertCount(2, $projectPagination->getDashboardPaginator(2, 'projects', 5)->setOrder(ProjectModel::TABLE.'.is_private')->getCollection());
        $this->assertCount(2, $projectPagination->getDashboardPaginator(2, 'projects', 5)->setOrder(ProjectModel::TABLE.'.name')->getCollection());
    }
}
