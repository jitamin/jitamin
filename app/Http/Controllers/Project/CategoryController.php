<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Project;

use Jitamin\Foundation\Exceptions\PageNotFoundException;
use Jitamin\Http\Controllers\Controller;

/**
 * Category Controller.
 */
class CategoryController extends Controller
{
    /**
     * List of categories for a given project.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws PageNotFoundException
     */
    public function index(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('project/category/index', [
            'categories' => $this->categoryModel->getList($project['id'], false),
            'values'     => $values + ['project_id' => $project['id']],
            'errors'     => $errors,
            'project'    => $project,
            'title'      => t('Categories'),
        ]));
    }

    /**
     * Validate and save a new category.
     */
    public function store()
    {
        $project = $this->getProject();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->categoryValidator->validateCreation($values);

        if ($valid) {
            if ($this->categoryModel->create($values) !== false) {
                $this->flash->success(t('Your category have been created successfully.'));

                return $this->response->redirect($this->helper->url->to('Project/CategoryController', 'index', ['project_id' => $project['id']]));
            } else {
                $this->flash->failure(t('Unable to create your category.'));
            }
        }

        return $this->index($values, $errors);
    }

    /**
     * Edit a category (display the form).
     *
     * @param array $values
     * @param array $errors
     *
     * @throws PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $project = $this->getProject();
        $category = $this->getCategory();

        $this->response->html($this->helper->layout->project('project/category/edit', [
            'values'  => empty($values) ? $category : $values,
            'errors'  => $errors,
            'project' => $project,
            'title'   => t('Categories'),
        ]));
    }

    /**
     * Edit a category (validate the form and update the database).
     */
    public function update()
    {
        $project = $this->getProject();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->categoryValidator->validateModification($values);

        if ($valid) {
            if ($this->categoryModel->update($values)) {
                $this->flash->success(t('Your category have been updated successfully.'));

                return $this->response->redirect($this->helper->url->to('Project/CategoryController', 'index', ['project_id' => $project['id']]));
            } else {
                $this->flash->failure(t('Unable to update your category.'));
            }
        }

        return $this->edit($values, $errors);
    }

    /**
     * Move category position.
     */
    public function move()
    {
        $project = $this->getProject();
        $values = $this->request->getJson();

        if (!empty($values) && isset($values['category_id']) && isset($values['position'])) {
            $result = $this->categoryModel->changePosition($project['id'], $values['category_id'], $values['position']);
            $this->response->json(['result' => $result]);
        } else {
            throw new AccessForbiddenException();
        }
    }

    /**
     * Remove a category.
     */
    public function remove()
    {
        $project = $this->getProject();
        $category = $this->getCategory();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->categoryModel->remove($category['id'])) {
                $this->flash->success(t('Category removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this category.'));
            }

            return $this->response->redirect($this->helper->url->to('Project/CategoryController', 'index', ['project_id' => $project['id']]));
        }

        return $this->response->html($this->helper->layout->project('project/category/remove', [
            'project'  => $project,
            'category' => $category,
            'title'    => t('Remove a category'),
        ]));
    }

    /**
     * Get the category (common method between actions).
     *
     * @throws PageNotFoundException
     *
     * @return array
     */
    protected function getCategory()
    {
        $category = $this->categoryModel->getById($this->request->getIntegerParam('category_id'));

        if (empty($category)) {
            throw new PageNotFoundException();
        }

        return $category;
    }
}
