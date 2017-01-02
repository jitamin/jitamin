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

use Jitamin\Controller\BaseController;
use Jitamin\Core\Controller\PageNotFoundException;

/**
 * Link Controller.
 */
class LinkController extends BaseController
{
    /**
     * Get the current link.
     *
     * @throws PageNotFoundException
     *
     * @return array
     */
    private function getLink()
    {
        $link = $this->linkModel->getById($this->request->getIntegerParam('link_id'));

        if (empty($link)) {
            throw new PageNotFoundException();
        }

        return $link;
    }

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
            'title'  => t('Task\'s links'),
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
     * Confirmation dialog before removing a link.
     */
    public function confirm()
    {
        $link = $this->getLink();

        $this->response->html($this->helper->layout->admin('admin/link/remove', [
            'link'  => $link,
            'title' => t('Remove a link'),
        ]));
    }

    /**
     * Remove a link.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $link = $this->getLink();

        if ($this->linkModel->remove($link['id'])) {
            $this->flash->success(t('Link removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this link.'));
        }

        $this->response->redirect($this->helper->url->to('Admin/LinkController', 'index'));
    }
}
