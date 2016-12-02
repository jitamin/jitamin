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

/**
 * Class GroupController.
 */
class GroupController extends BaseController
{
    /**
     * Display a form to create a new group.
     *
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = [], array $errors = [])
    {
        $this->response->html($this->template->render('group/create', [
            'errors' => $errors,
            'values' => $values,
        ]));
    }

    /**
     * Validate and save a new group.
     */
    public function store()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->groupValidator->validateCreation($values);

        if ($valid) {
            if ($this->groupModel->create($values['name']) !== false) {
                $this->flash->success(t('Group created successfully.'));

                return $this->response->redirect($this->helper->url->to('GroupListController', 'index'), true);
            } else {
                $this->flash->failure(t('Unable to create your group.'));
            }
        }

        return $this->show($values, $errors);
    }

    /**
     * Display a form to update a group.
     *
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = [], array $errors = [])
    {
        if (empty($values)) {
            $values = $this->groupModel->getById($this->request->getIntegerParam('group_id'));
        }

        $this->response->html($this->template->render('group/edit', [
            'errors' => $errors,
            'values' => $values,
        ]));
    }

    /**
     * Validate and save a group.
     */
    public function update()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->groupValidator->validateModification($values);

        if ($valid) {
            if ($this->groupModel->update($values) !== false) {
                $this->flash->success(t('Group updated successfully.'));

                return $this->response->redirect($this->helper->url->to('GroupListController', 'index'), true);
            } else {
                $this->flash->failure(t('Unable to update your group.'));
            }
        }

        return $this->show($values, $errors);
    }
}
