<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

use Hiject\Core\Controller\PageNotFoundException;

/**
 * Category Controller.
 */
class CategoryController extends BaseController
{
    /**
     * Get the category (common method between actions).
     *
     * @throws PageNotFoundException
     *
     * @return array
     */
    private function getCategory()
    {
        $category = $this->categoryModel->getById($this->request->getIntegerParam('category_id'));

        if (empty($category)) {
            throw new PageNotFoundException();
        }

        return $category;
    }

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

        $this->response->html($this->helper->layout->project('category/index', [
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
    public function save()
    {
        $project = $this->getProject();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->categoryValidator->validateCreation($values);

        if ($valid) {
            if ($this->categoryModel->create($values) !== false) {
                $this->flash->success(t('Your category have been created successfully.'));

                return $this->response->redirect($this->helper->url->to('CategoryController', 'index', ['project_id' => $project['id']]));
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

        $this->response->html($this->helper->layout->project('category/edit', [
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

                return $this->response->redirect($this->helper->url->to('CategoryController', 'index', ['project_id' => $project['id']]));
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
     * Confirmation dialog before removing a category.
     */
    public function confirm()
    {
        $project = $this->getProject();
        $category = $this->getCategory();

        $this->response->html($this->helper->layout->project('category/remove', [
            'project'  => $project,
            'category' => $category,
            'title'    => t('Remove a category'),
        ]));
    }

    /**
     * Remove a category.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $project = $this->getProject();
        $category = $this->getCategory();

        if ($this->categoryModel->remove($category['id'])) {
            $this->flash->success(t('Category removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this category.'));
        }

        $this->response->redirect($this->helper->url->to('CategoryController', 'index', ['project_id' => $project['id']]));
    }
}
