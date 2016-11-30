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
 * Class GroupCreationController.
 */
class GroupCreationController extends BaseController
{
    /**
     * Display a form to create a new group.
     *
     * @param array $values
     * @param array $errors
     */
    public function show(array $values = [], array $errors = [])
    {
        $this->response->html($this->template->render('group_creation/show', [
            'errors' => $errors,
            'values' => $values,
        ]));
    }

    /**
     * Validate and save a new group.
     */
    public function save()
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
}
