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

use Hiject\Model\CategoryModel;
use Hiject\Model\SettingModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;

class CategoryModelTest extends Base
{
    public function testCreation()
    {
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1]));
        $this->assertEquals(2, $categoryModel->create(['name' => 'Category #2', 'project_id' => 1]));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'Task #1', 'project_id' => 1, 'category_id' => 2]));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals(2, $task['category_id']);

        $category = $categoryModel->getById(2);
        $this->assertEquals(2, $category['id']);
        $this->assertEquals('Category #2', $category['name']);
        $this->assertEquals(1, $category['project_id']);
    }

    public function testExists()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1]));
        $this->assertTrue($categoryModel->exists(1));
        $this->assertFalse($categoryModel->exists(2));
    }

    public function testGetById()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1, 'description' => 'test']));

        $category = $categoryModel->getById(1);
        $this->assertEquals(1, $category['id']);
        $this->assertEquals('Category #1', $category['name']);
        $this->assertEquals(1, $category['project_id']);
        $this->assertEquals('test', $category['description']);
    }

    public function testGetNameById()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1, 'description' => 'test']));

        $this->assertEquals('Category #1', $categoryModel->getNameById(1));
        $this->assertEquals('', $categoryModel->getNameById(2));
    }

    public function testGetIdByName()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1, 'description' => 'test']));

        $this->assertSame(1, $categoryModel->getIdByName(1, 'Category #1'));
        $this->assertSame(0, $categoryModel->getIdByName(1, 'Category #2'));
    }

    public function testGetProjectId()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1, 'description' => 'test']));

        $this->assertEquals(1, $categoryModel->getProjectId(1));
        $this->assertSame(0, $categoryModel->getProjectId(2));
    }

    public function testGetList()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1, 'description' => 'test']));
        $this->assertEquals(2, $categoryModel->create(['name' => 'Category #2', 'project_id' => 1]));

        $categories = $categoryModel->getList(1, false, false);
        $this->assertCount(2, $categories);
        $this->assertEquals('Category #1', $categories[1]);
        $this->assertEquals('Category #2', $categories[2]);

        $categories = $categoryModel->getList(1, true, false);
        $this->assertCount(3, $categories);
        $this->assertEquals('No category', $categories[0]);
        $this->assertEquals('Category #1', $categories[1]);
        $this->assertEquals('Category #2', $categories[2]);

        $categories = $categoryModel->getList(1, false, true);
        $this->assertCount(3, $categories);
        $this->assertEquals('All categories', $categories[-1]);
        $this->assertEquals('Category #1', $categories[1]);
        $this->assertEquals('Category #2', $categories[2]);

        $categories = $categoryModel->getList(1, true, true);
        $this->assertCount(4, $categories);
        $this->assertEquals('All categories', $categories[-1]);
        $this->assertEquals('No category', $categories[0]);
        $this->assertEquals('Category #1', $categories[1]);
        $this->assertEquals('Category #2', $categories[2]);
    }

    public function testGetAll()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1, 'description' => 'test']));
        $this->assertEquals(2, $categoryModel->create(['name' => 'Category #2', 'project_id' => 1]));

        $categories = $categoryModel->getAll(1);
        $this->assertCount(2, $categories);

        $this->assertEquals('Category #1', $categories[0]['name']);
        $this->assertEquals('test', $categories[0]['description']);
        $this->assertEquals(1, $categories[0]['project_id']);
        $this->assertEquals(1, $categories[0]['id']);

        $this->assertEquals('Category #2', $categories[1]['name']);
        $this->assertEquals('', $categories[1]['description']);
        $this->assertEquals(1, $categories[1]['project_id']);
        $this->assertEquals(2, $categories[1]['id']);
    }

    public function testCreateDefaultCategories()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);
        $settingModel = new SettingModel($this->container);

        $this->assertTrue($settingModel->save(['project_categories' => 'C1, C2, C3']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertTrue($categoryModel->createDefaultCategories(1));

        $categories = $categoryModel->getAll(1);
        $this->assertCount(3, $categories);
        $this->assertEquals('C1', $categories[0]['name']);
        $this->assertEquals('C2', $categories[1]['name']);
        $this->assertEquals('C3', $categories[2]['name']);
    }

    public function testUpdate()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1]));
        $this->assertTrue($categoryModel->update(['id' => 1, 'description' => 'test']));

        $category = $categoryModel->getById(1);
        $this->assertEquals('Category #1', $category['name']);
        $this->assertEquals(1, $category['project_id']);
        $this->assertEquals('test', $category['description']);
    }

    public function testRemove()
    {
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1]));
        $this->assertEquals(2, $categoryModel->create(['name' => 'Category #2', 'project_id' => 1]));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'Task #1', 'project_id' => 1, 'category_id' => 2]));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals(2, $task['category_id']);

        $this->assertTrue($categoryModel->remove(1));
        $this->assertTrue($categoryModel->remove(2));

        // Make sure tasks assigned with that category are reseted
        $task = $taskFinderModel->getById(1);
        $this->assertEquals(0, $task['category_id']);
    }

    public function testDuplicate()
    {
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project #2']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'Category #1', 'project_id' => 1, 'description' => 'test']));

        $this->assertTrue($categoryModel->duplicate(1, 2));

        $category = $categoryModel->getById(1);
        $this->assertEquals('Category #1', $category['name']);
        $this->assertEquals(1, $category['project_id']);
        $this->assertEquals('test', $category['description']);

        $category = $categoryModel->getById(2);
        $this->assertEquals('Category #1', $category['name']);
        $this->assertEquals(2, $category['project_id']);
        $this->assertEquals('test', $category['description']);
    }
}
