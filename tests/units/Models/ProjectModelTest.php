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

use Jitamin\Bus\Subscriber\ProjectModificationDateSubscriber;
use Jitamin\Foundation\Translator;
use Jitamin\Model\CategoryModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\SettingModel;
use Jitamin\Model\TaskModel;
use Jitamin\Model\UserModel;

class ProjectModelTest extends Base
{
    public function testCreationForAllLanguages()
    {
        $projectModel = new ProjectModel($this->container);

        foreach ($this->container['languageModel']->getLanguages() as $locale => $language) {
            Translator::unload();
            Translator::load($locale);
            $this->assertNotFalse($projectModel->create(['name' => 'UnitTest '.$locale]), 'Unable to create project with '.$locale.':'.$language);
        }

        Translator::unload();
    }

    public function testCreation()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals(1, $project['is_active']);
        $this->assertEquals(0, $project['is_public']);
        $this->assertEquals(0, $project['is_private']);
        $this->assertEquals(time(), $project['last_modified'], '', 1);
        $this->assertEmpty($project['token']);
        $this->assertEmpty($project['start_date']);
        $this->assertEmpty($project['end_date']);
    }

    public function testProjectDate()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertTrue($projectModel->update([
            'id'         => 1,
            'start_date' => '2016-08-31',
            'end_date'   => '08/31/2016',
        ]));

        $project = $projectModel->getById(1);
        $this->assertEquals('2016-08-31', $project['start_date']);
        $this->assertEquals('2016-08-31', $project['end_date']);
    }

    public function testCreationWithDuplicateName()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertEquals(2, $projectModel->create(['name' => 'UnitTest']));
    }

    public function testCreationWithStartAndDate()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest', 'start_date' => '2015-01-01', 'end_date' => '2015-12-31']));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals('2015-01-01', $project['start_date']);
        $this->assertEquals('2015-12-31', $project['end_date']);
    }

    public function testCreationWithDefaultCategories()
    {
        $projectModel = new ProjectModel($this->container);
        $settingModel = new SettingModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        // Multiple categories correctly formatted

        $this->assertTrue($settingModel->save(['project_categories' => 'Test1, Test2']));
        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest1']));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);

        $categories = $categoryModel->getAll(1);
        $this->assertNotEmpty($categories);
        $this->assertEquals(2, count($categories));
        $this->assertEquals('Test1', $categories[0]['name']);
        $this->assertEquals('Test2', $categories[1]['name']);

        // Single category

        $this->assertTrue($settingModel->save(['project_categories' => 'Test1']));
        $this->container['memoryCache']->flush();
        $this->assertEquals(2, $projectModel->create(['name' => 'UnitTest2']));

        $project = $projectModel->getById(2);
        $this->assertNotEmpty($project);

        $categories = $categoryModel->getAll(2);
        $this->assertNotEmpty($categories);
        $this->assertEquals(1, count($categories));
        $this->assertEquals('Test1', $categories[0]['name']);

        // Multiple categories badly formatted

        $this->assertTrue($settingModel->save(['project_categories' => 'ABC, , DEF 3,  ']));
        $this->container['memoryCache']->flush();
        $this->assertEquals(3, $projectModel->create(['name' => 'UnitTest3']));

        $project = $projectModel->getById(3);
        $this->assertNotEmpty($project);

        $categories = $categoryModel->getAll(3);
        $this->assertNotEmpty($categories);
        $this->assertEquals(2, count($categories));
        $this->assertEquals('ABC', $categories[0]['name']);
        $this->assertEquals('DEF 3', $categories[1]['name']);

        // No default categories
        $this->assertTrue($settingModel->save(['project_categories' => '  ']));
        $this->container['memoryCache']->flush();
        $this->assertEquals(4, $projectModel->create(['name' => 'UnitTest4']));

        $project = $projectModel->getById(4);
        $this->assertNotEmpty($project);

        $categories = $categoryModel->getAll(4);
        $this->assertEmpty($categories);
    }

    public function testUpdateLastModifiedDate()
    {
        $projectModel = new ProjectModel($this->container);
        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));

        $now = time();

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals($now, $project['last_modified'], 'Wrong Timestamp', 1);

        sleep(1);
        $this->assertTrue($projectModel->updateModificationDate(1));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertGreaterThan($now, $project['last_modified']);
    }

    public function testGetAllIds()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));

        $this->assertEmpty($projectModel->getAllByIds([]));
        $this->assertNotEmpty($projectModel->getAllByIds([1, 2]));
        $this->assertCount(1, $projectModel->getAllByIds([1]));
    }

    public function testIsLastModified()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);

        $now = time();

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals($now, $project['last_modified']);

        sleep(1);

        $listener = new ProjectModificationDateSubscriber($this->container);
        $this->container['dispatcher']->addListener(TaskModel::EVENT_CREATE_UPDATE, [$listener, 'execute']);

        $this->assertEquals(1, $taskModel->create(['title' => 'Task #1', 'project_id' => 1]));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_CREATE_UPDATE.'.Jitamin\Bus\Subscriber\ProjectModificationDateSubscriber::execute', $called);

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertTrue($projectModel->isModifiedSince(1, $now));
    }

    public function testRemove()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertTrue($projectModel->remove(1));
        $this->assertFalse($projectModel->remove(1234));
    }

    public function testEnable()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertTrue($projectModel->disable(1));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals(0, $project['is_active']);

        $this->assertFalse($projectModel->disable(1111));
    }

    public function testDisable()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertTrue($projectModel->disable(1));
        $this->assertTrue($projectModel->enable(1));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals(1, $project['is_active']);

        $this->assertFalse($projectModel->enable(1234567));
    }

    public function testEnablePublicAccess()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertTrue($projectModel->enablePublicAccess(1));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals(1, $project['is_public']);
        $this->assertNotEmpty($project['token']);

        $this->assertFalse($projectModel->enablePublicAccess(123));
    }

    public function testDisablePublicAccess()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertTrue($projectModel->enablePublicAccess(1));
        $this->assertTrue($projectModel->disablePublicAccess(1));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals(0, $project['is_public']);
        $this->assertEmpty($project['token']);

        $this->assertFalse($projectModel->disablePublicAccess(123));
    }

    public function testIdentifier()
    {
        $projectModel = new ProjectModel($this->container);

        // Creation
        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest1', 'identifier' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'UnitTest2']));

        $project = $projectModel->getById(1);
        $this->assertNotEmpty($project);
        $this->assertEquals('TEST1', $project['identifier']);

        $project = $projectModel->getById(2);
        $this->assertNotEmpty($project);
        $this->assertEquals('', $project['identifier']);

        // Update
        $this->assertTrue($projectModel->update(['id' => '2', 'identifier' => 'test2']));

        $project = $projectModel->getById(2);
        $this->assertNotEmpty($project);
        $this->assertEquals('TEST2', $project['identifier']);

        $project = $projectModel->getByIdentifier('test1');
        $this->assertNotEmpty($project);
        $this->assertEquals('TEST1', $project['identifier']);

        $project = $projectModel->getByIdentifier('');
        $this->assertFalse($project);
    }

    public function testThatProjectCreatorAreAlsoOwner()
    {
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);

        $this->assertEquals(2, $userModel->create(['username' => 'user1', 'email' => 'user1@here', 'name' => 'Me']));
        $this->assertEquals(1, $projectModel->create(['name' => 'My project 1'], 2));
        $this->assertEquals(2, $projectModel->create(['name' => 'My project 2']));

        $project = $projectModel->getByIdWithOwner(1);
        $this->assertNotEmpty($project);
        $this->assertSame('My project 1', $project['name']);
        $this->assertSame('Me', $project['owner_name']);
        $this->assertSame('user1', $project['owner_username']);
        $this->assertEquals(2, $project['owner_id']);

        $project = $projectModel->getByIdWithOwner(2);
        $this->assertNotEmpty($project);
        $this->assertSame('My project 2', $project['name']);
        $this->assertEquals('', $project['owner_name']);
        $this->assertEquals('', $project['owner_username']);
        $this->assertEquals(0, $project['owner_id']);
    }
}
