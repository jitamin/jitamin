<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Manage;

use Jitamin\Controller\Controller;
use Jitamin\Core\Controller\AccessForbiddenException;

/**
 * Class ProjectTagController.
 */
class ProjectTagController extends Controller
{
    /**
     * List of project tags.
     */
    public function index()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('manage/project_tag/index', [
            'project' => $project,
            'tags'    => $this->tagModel->getAllByProject($project['id']),
            'title'   => t('Project tags management'),
        ]));
    }

    /**
     * Show form to create new project tag.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     */
    public function create(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        if (empty($values)) {
            $values['project_id'] = $project['id'];
        }

        $this->response->html($this->template->render('manage/project_tag/create', [
            'project' => $project,
            'values'  => $values,
            'errors'  => $errors,
        ]));
    }

    /**
     * Validate and save a new project tag.
     */
    public function store()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->tagValidator->validateCreation($values);

        if ($valid) {
            if ($this->tagModel->create($project['id'], $values['name']) > 0) {
                $this->flash->success(t('Tag created successfully.'));
            } else {
                $this->flash->failure(t('Unable to create this tag.'));
            }

            $this->response->redirect($this->helper->url->to('Manage/ProjectTagController', 'index', ['project_id' => $project['id']]));
        } else {
            $this->create($values, $errors);
        }
    }

    /**
     * Show form to update a project tag.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $project = $this->getProject();
        $tag_id = $this->request->getIntegerParam('tag_id');
        $tag = $this->tagModel->getById($tag_id);

        if (empty($values)) {
            $values = $tag;
        }

        $this->response->html($this->template->render('manage/project_tag/edit', [
            'project' => $project,
            'tag'     => $tag,
            'values'  => $values,
            'errors'  => $errors,
        ]));
    }

    /**
     * Validate and update a project tag.
     */
    public function update()
    {
        $project = $this->getProject();
        $tag_id = $this->request->getIntegerParam('tag_id');
        $tag = $this->tagModel->getById($tag_id);
        $values = $this->request->getValues();
        list($valid, $errors) = $this->tagValidator->validateModification($values);

        if ($tag['project_id'] != $project['id']) {
            throw new AccessForbiddenException();
        }

        if ($valid) {
            if ($this->tagModel->update($values['id'], $values['name'])) {
                $this->flash->success(t('Tag updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update this tag.'));
            }

            $this->response->redirect($this->helper->url->to('Manage/ProjectTagController', 'index', ['project_id' => $project['id']]));
        } else {
            $this->edit($values, $errors);
        }
    }

    /**
     * Remove a project tag.
     */
    public function remove()
    {

        $project = $this->getProject();
        $tag_id = $this->request->getIntegerParam('tag_id');
        $tag = $this->tagModel->getById($tag_id);

        if ($tag['project_id'] != $project['id']) {
            throw new AccessForbiddenException();
        }

        if ($this->request->isPost()) {
            if ($this->request->checkCSRFToken() && $this->tagModel->remove($tag_id)) {
                $this->flash->success(t('Tag removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this tag.'));
            }

            return $this->response->redirect($this->helper->url->to('Manage/ProjectTagController', 'index', ['project_id' => $project['id']]));
        }

        return $this->response->html($this->template->render('manage/project_tag/remove', [
            'tag'     => $tag,
            'project' => $project,
        ]));
    }
}
