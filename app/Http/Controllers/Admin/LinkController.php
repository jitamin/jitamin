<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Admin;

use Jitamin\Foundation\Exceptions\PageNotFoundException;
use Jitamin\Http\Controllers\Controller;

/**
 * Link Controller.
 */
class LinkController extends Controller
{
    /**
     * List of links.
     *
     * @param array $values
     * @param array $errors
     */
    public function index(array $values = [], array $errors = [])
    {
        $this->response->html($this->helper->layout->admin('admin/link/index', [
            'links'  => $this->linkModel->getMergedList(),
            'values' => $values,
            'errors' => $errors,
            'title'  => t('Admin').' &raquo; '.t('Link settings'),
        ], 'admin/link/subside'));
    }

    /**
     * Display a form to create a new tag.
     *
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = [], array $errors = [])
    {
        $this->response->html($this->template->render('admin/link/create', [
            'values' => $values,
            'errors' => $errors,
        ]));
    }

    /**
     * Validate and store a new link.
     */
    public function store()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->linkValidator->validateCreation($values);

        if ($valid) {
            if ($this->linkModel->create($values['label'], $values['opposite_label']) !== false) {
                $this->flash->success(t('Link added successfully.'));

                return $this->response->redirect($this->helper->url->to('Admin/LinkController', 'index'));
            } else {
                $this->flash->failure(t('Unable to create your link.'));
            }
        }

        return $this->index($values, $errors);
    }

    /**
     * Edit form.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $link = $this->getLink();
        $link['label'] = t($link['label']);

        $this->response->html($this->helper->layout->admin('admin/link/edit', [
            'values' => $values ?: $link,
            'errors' => $errors,
            'labels' => $this->linkModel->getList($link['id']),
            'link'   => $link,
            'title'  => t('Link modification'),
        ]));
    }

    /**
     * Edit a link (validate the form and update the database).
     */
    public function update()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->linkValidator->validateModification($values);

        if ($valid) {
            if ($this->linkModel->update($values)) {
                $this->flash->success(t('Link updated successfully.'));

                return $this->response->redirect($this->helper->url->to('Admin/LinkController', 'index'));
            } else {
                $this->flash->failure(t('Unable to update your link.'));
            }
        }

        return $this->edit($values, $errors);
    }

    /**
     * Remove a link.
     */
    public function remove()
    {
        $link = $this->getLink();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->linkModel->remove($link['id'])) {
                $this->flash->success(t('Link removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this link.'));
            }

            return $this->response->redirect($this->helper->url->to('Admin/LinkController', 'index'));
        }

        return $this->response->html($this->helper->layout->admin('admin/link/remove', [
            'link'  => $link,
            'title' => t('Remove a link'),
        ]));
    }

    /**
     * Get the current link.
     *
     * @throws PageNotFoundException
     *
     * @return array
     */
    protected function getLink()
    {
        $link = $this->linkModel->getById($this->request->getIntegerParam('link_id'));

        if (empty($link)) {
            throw new PageNotFoundException();
        }

        return $link;
    }
}
