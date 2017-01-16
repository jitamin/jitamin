<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Model\ProjectModel;
use Jitamin\Model\ProjectStarModel;
use Jitamin\Model\UserModel;
use Jitamin\Pagination\StarPagination;

require_once __DIR__.'/../Base.php';

class StarPaginationTest extends Base
{
    public function testDashboardPagination()
    {
        $projectModel = new ProjectModel($this->container);
        $projectStarModel = new ProjectStarModel($this->container);
        $userModel = new UserModel($this->container);
        $starPagination = new StarPagination($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));

        $this->assertEquals(2, $userModel->create(['username' => 'test', 'email' => 'test@test']));
        $this->assertTrue($projectStarModel->addStargazer(1, 2));

        $this->assertCount(1, $starPagination->getDashboardPaginator(2, 'starred', 5)->getCollection());
        $this->assertCount(0, $starPagination->getDashboardPaginator(1, 'starred', 5)->getCollection());
    }
}
