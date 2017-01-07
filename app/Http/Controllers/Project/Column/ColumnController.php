<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Project\Column;

use Jitamin\Controller\Controller;
use Jitamin\Core\Controller\AccessForbiddenException;

/**
 * Column Controller.
 */
class ColumnController extends Controller
{
    /**
     * Display columns list.
     */
    public function index()
    {
        $project = $this->getProject();
        $columns = $this->columnModel->getAll($project['id']);

        $this->response->html($this->helper->layout->project('project/column/index', [
            'columns' => $columns,
            'project' => $project,
            'title'   => t('Edit columns'),
        ]));
    }

    /**
     * Show form to create a new column.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Jitamin\Core\Controller\PageNotFoundException
     */
    public function create(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        if (empty($values)) {
            $values = ['project_id' => $project['id']];
        }

        $this->response->html($this->template->render('project/column/create', [
            'values'  => $values,
            'errors'  => $errors,
            'project' => $project,
        ]));
    }

    /**
     * Validate and add a new column.
     */
    public function store()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->columnValidator->validateCreation($values);

        if ($valid) {
            $result = $this->columnModel->create(
                $project['id'],
                $values['title'],
                $values['task_limit'],
                $values['description'],
                isset($values['hide_in_dashboard']) ? $values['hide_in_dashboard'] : 0
            );

            if ($result !== false) {
                $this->flash->success(t('Column created successfully.'));

                return $this->response->redirect($this->helper->url->to('Project/Column/ColumnController', 'index', ['project_id' => $project['id']]), true);
            } else {
                $errors['title'] = [t('Another column with the same name exists in the project')];
            }
        }

        return $this->create($values, $errors);
    }

    /**
     * Display a form to edit a column.
     *
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = [], array $errors = [])
    {
        $project = $this->getProject();
        $column = $this->columnModel->getById($this->request->getIntegerParam('column_id'));

        $this->response->html($this->helper->layout->project('project/column/edit', [
            'errors'  => $errors,
            'values'  => $values ?: $column,
            'project' => $project,
            'column'  => $column,
        ]));
    }

    /**
     * Validate and update a column.
     */
    public function update()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->columnValidator->validateModification($values);

        if ($valid) {
            $result = $this->columnModel->update(
                $values['id'],
                $values['title'],
                $values['task_limit'],
                $values['description'],
                isset($values['hide_in_dashboard']) ? $values['hide_in_dashboard'] : 0
            );

            if ($result) {
                $this->flash->success(t('Board updated successfully.'));

                return $this->response->redirect($this->helper->url->to('Project/Column/ColumnController', 'index', ['project_id' => $project['id']]));
            } else {
                $this->flash->failure(t('Unable to update this board.'));
            }
        }

        return $this->edit($values, $errors);
    }

    /**
     * Move column position.
     */
    public function move()
    {
        $project = $this->getProject();
        $values = $this->request->getJson();

        if (!empty($values) && isset($values['column_id']) && isset($values['position'])) {
            $result = $this->columnModel->changePosition($project['id'], $values['column_id'], $values['position']);
            $this->response->json(['result' => $result]);
        } else {
            throw new AccessForbiddenException();
        }
    }

    /**
     * Confirm column suppression.
     */
    public function confirm()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('project/column/remove', [
            'column'  => $this->columnModel->getById($this->request->getIntegerParam('column_id')),
            'project' => $project,
        ]));
    }

    /**
     * Remove a column.
     */
    public function remove()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();
        $column_id = $this->request->getIntegerParam('column_id');

        if ($this->columnModel->remove($column_id)) {
            $this->flash->success(t('Column removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this column.'));
        }

        $this->response->redirect($this->helper->url->to('Project/Column/ColumnController', 'index', ['project_id' => $project['id']]));
    }
}
