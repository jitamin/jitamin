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
use Hiject\Model\UserModel;
use Hiject\Model\UserNotificationFilterModel;
use Hiject\Model\UserNotificationModel;

class UserNotificationFilterTest extends Base
{
    public function testGetFilters()
    {
        $nf = new UserNotificationFilterModel($this->container);
        $filters = $nf->getFilters();
        $this->assertArrayHasKey(UserNotificationFilterModel::FILTER_NONE, $filters);
        $this->assertArrayHasKey(UserNotificationFilterModel::FILTER_BOTH, $filters);
        $this->assertArrayHasKey(UserNotificationFilterModel::FILTER_CREATOR, $filters);
        $this->assertArrayHasKey(UserNotificationFilterModel::FILTER_ASSIGNEE, $filters);
    }

    public function testSaveProjectFilter()
    {
        $nf = new UserNotificationFilterModel($this->container);
        $p = new ProjectModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1']));
        $this->assertEquals(2, $p->create(['name' => 'UnitTest2']));
        $this->assertEquals(3, $p->create(['name' => 'UnitTest3']));

        $this->assertEmpty($nf->getSelectedProjects(1));
        $this->assertTrue($nf->saveSelectedProjects(1, [1, 2, 3]));
        $this->assertEquals([1, 2, 3], $nf->getSelectedProjects(1));
    }

    public function testSaveUserFilter()
    {
        $nf = new UserNotificationFilterModel($this->container);

        $this->assertEquals(UserNotificationFilterModel::FILTER_BOTH, $nf->getSelectedFilter(1));
        $nf->saveFilter(1, UserNotificationFilterModel::FILTER_CREATOR);
        $this->assertEquals(UserNotificationFilterModel::FILTER_CREATOR, $nf->getSelectedFilter(1));
    }

    public function testFilterNone()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user1', 'notifications_filter' => UserNotificationFilterModel::FILTER_NONE]));
        $this->assertTrue($n->filterNone($u->getById(2)));

        $this->assertEquals(3, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_BOTH]));
        $this->assertFalse($n->filterNone($u->getById(3)));
    }

    public function testFilterCreator()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user1', 'notifications_filter' => UserNotificationFilterModel::FILTER_CREATOR]));
        $this->assertTrue($n->filterCreator($u->getById(2), ['task' => ['creator_id' => 2]]));

        $this->assertEquals(3, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_CREATOR]));
        $this->assertFalse($n->filterCreator($u->getById(3), ['task' => ['creator_id' => 1]]));

        $this->assertEquals(4, $u->create(['username' => 'user3', 'notifications_filter' => UserNotificationFilterModel::FILTER_NONE]));
        $this->assertFalse($n->filterCreator($u->getById(4), ['task' => ['creator_id' => 2]]));
    }

    public function testFilterAssignee()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user1', 'notifications_filter' => UserNotificationFilterModel::FILTER_ASSIGNEE]));
        $this->assertTrue($n->filterAssignee($u->getById(2), ['task' => ['owner_id' => 2]]));

        $this->assertEquals(3, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_ASSIGNEE]));
        $this->assertFalse($n->filterAssignee($u->getById(3), ['task' => ['owner_id' => 1]]));

        $this->assertEquals(4, $u->create(['username' => 'user3', 'notifications_filter' => UserNotificationFilterModel::FILTER_NONE]));
        $this->assertFalse($n->filterAssignee($u->getById(4), ['task' => ['owner_id' => 2]]));
    }

    public function testFilterBoth()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user1', 'notifications_filter' => UserNotificationFilterModel::FILTER_BOTH]));
        $this->assertTrue($n->filterBoth($u->getById(2), ['task' => ['owner_id' => 2, 'creator_id' => 1]]));
        $this->assertTrue($n->filterBoth($u->getById(2), ['task' => ['owner_id' => 0, 'creator_id' => 2]]));

        $this->assertEquals(3, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_BOTH]));
        $this->assertFalse($n->filterBoth($u->getById(3), ['task' => ['owner_id' => 1, 'creator_id' => 1]]));
        $this->assertFalse($n->filterBoth($u->getById(3), ['task' => ['owner_id' => 2, 'creator_id' => 1]]));

        $this->assertEquals(4, $u->create(['username' => 'user3', 'notifications_filter' => UserNotificationFilterModel::FILTER_NONE]));
        $this->assertFalse($n->filterBoth($u->getById(4), ['task' => ['owner_id' => 2, 'creator_id' => 1]]));
    }

    public function testFilterProject()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationModel($this->container);
        $nf = new UserNotificationFilterModel($this->container);
        $p = new ProjectModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1']));
        $this->assertEquals(2, $p->create(['name' => 'UnitTest2']));

        // No project selected
        $this->assertTrue($nf->filterProject($u->getById(1), []));

        // User that select only some projects
        $this->assertEquals(2, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_NONE]));
        $n->saveSettings(2, ['notifications_enabled' => 1, 'notification_projects' => [2 => true]]);

        $this->assertFalse($nf->filterProject($u->getById(2), ['task' => ['project_id' => 1]]));
        $this->assertTrue($nf->filterProject($u->getById(2), ['task' => ['project_id' => 2]]));
    }

    public function testFilterUserWithNoFilter()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_NONE]));

        $this->assertTrue($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1]]));
    }

    public function testFilterUserWithAssigneeFilter()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_ASSIGNEE]));

        $this->assertTrue($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'owner_id' => 2]]));
        $this->assertFalse($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'owner_id' => 1]]));
    }

    public function testFilterUserWithCreatorFilter()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_CREATOR]));

        $this->assertTrue($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 2]]));
        $this->assertFalse($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 1]]));
    }

    public function testFilterUserWithBothFilter()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationFilterModel($this->container);

        $this->assertEquals(2, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_BOTH]));

        $this->assertTrue($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 2, 'owner_id' => 3]]));
        $this->assertTrue($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 0, 'owner_id' => 2]]));
        $this->assertFalse($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 4, 'owner_id' => 1]]));
        $this->assertFalse($n->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 5, 'owner_id' => 0]]));
    }

    public function testFilterUserWithBothFilterAndProjectSelected()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationModel($this->container);
        $nf = new UserNotificationFilterModel($this->container);
        $p = new ProjectModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1']));
        $this->assertEquals(2, $p->create(['name' => 'UnitTest2']));

        $this->assertEquals(2, $u->create(['username' => 'user2', 'notifications_filter' => UserNotificationFilterModel::FILTER_BOTH]));

        $n->saveSettings(2, ['notifications_enabled' => 1, 'notification_projects' => [2 => true]]);

        $this->assertFalse($nf->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 2, 'owner_id' => 3]]));
        $this->assertFalse($nf->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 1, 'creator_id' => 0, 'owner_id' => 2]]));

        $this->assertTrue($nf->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 2, 'creator_id' => 2, 'owner_id' => 3]]));
        $this->assertTrue($nf->shouldReceiveNotification($u->getById(2), ['task' => ['project_id' => 2, 'creator_id' => 0, 'owner_id' => 2]]));
    }
}
