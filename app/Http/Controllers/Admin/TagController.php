<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Admin;

use Jitamin\Controller\Controller;
use Jitamin\Core\Controller\AccessForbiddenException;

/**
 * Class TagController.
 */
class TagController extends Controller
{
    /**
     * List all tags.
     */
    public function index()
    {
        $this->response->html($this->helper->layout->admin('admin/tag/index', [
            'tags'  => $this->tagModel->getAllByProject(0),
            'title' => t('Global tags management'),
        ], 'admin/tag/subside'));
    }

    /**
     * Display a form to create a new tag.
     *
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = [], array $errors = [])
    {
        if (empty($values)) {
            $values['project_id'] = 0;
        }

        $this->response->html($this->template->render('admin/tag/create', [
            'values' => $values,
            'errors' => $errors,
        ]));
    }

    /**
     * Validate and save a new user.
     */
    public function store()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->tagValidator->validateCreation($values);

        if ($valid) {
            if ($this->tagModel->create(0, $values['name']) > 0) {
                $this->flash->success(t('Tag created successfully.'));
            } else {
                $this->flash->failure(t('Unable to create this tag.'));
            }

            $this->response->redirect($this->helper->url->to('Admin/TagController', 'index'));
        } else {
            $this->create($values, $errors);
        }
    }

    /**
     * Display a form to update a tag.
     *
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = [], array $errors = [])
    {
        $tag_id = $this->request->getIntegerParam('tag_id');
        $tag = $this->tagModel->getById($tag_id);

        if (empty($values)) {
            $values = $tag;
        }

        $this->response->html($this->template->render('admin/tag/edit', [
            'tag'    => $tag,
            'values' => $values,
            'errors' => $errors,
        ]));
    }

    /**
     * Validate and update a tag.
     */
    public function update()
    {
        $tag_id = $this->request->getIntegerParam('tag_id');
        $tag = $this->tagModel->getById($tag_id);
        $values = $this->request->getValues();
        list($valid, $errors) = $this->tagValidator->validateModification($values);

        if ($tag['project_id'] != 0) {
            throw new AccessForbiddenException();
        }

        if ($valid) {
            if ($this->tagModel->update($values['id'], $values['name'])) {
                $this->flash->success(t('Tag updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update this tag.'));
            }

            $this->response->redirect($this->helper->url->to('Admin/TagController', 'index'));
        } else {
            $this->edit($values, $errors);
        }
    }

    /**
     * Confirmation dialog to remove a tag.
     */
    public function confirm()
    {
        $tag_id = $this->request->getIntegerParam('tag_id');
        $tag = $this->tagModel->getById($tag_id);

        $this->response->html($this->template->render('admin/tag/remove', [
            'tag' => $tag,
        ]));
    }

    /**
     * Remove a tag.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $tag_id = $this->request->getIntegerParam('tag_id');
        $tag = $this->tagModel->getById($tag_id);

        if ($tag['project_id'] != 0) {
            throw new AccessForbiddenException();
        }

        if ($this->tagModel->remove($tag_id)) {
            $this->flash->success(t('Tag removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this tag.'));
        }

        $this->response->redirect($this->helper->url->to('Admin/TagController', 'index'));
    }
}
