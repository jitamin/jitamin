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
 * Class GroupModificationController
 */
class GroupModificationController extends BaseController
{
    /**
     * Display a form to update a group
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
    public function show(array $values = [], array $errors = [])
    {
        if (empty($values)) {
            $values = $this->groupModel->getById($this->request->getIntegerParam('group_id'));
        }

        $this->response->html($this->template->render('group_modification/show', [
            'errors' => $errors,
            'values' => $values,
        ]));
    }

    /**
     * Validate and save a group
     *
     * @access public
     */
    public function save()
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
