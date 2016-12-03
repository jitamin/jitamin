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
 * Class ProfileController.
 */
class ProfileController extends BaseController
{
    /**
     * Display a form to edit user information.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Hiject\Core\Controller\AccessForbiddenException
     * @throws \Hiject\Core\Controller\PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $user = $this->getUser();

        if (empty($values)) {
            $values = $user;
            unset($values['password']);
        }

        return $this->response->html($this->helper->layout->user('profile/edit', [
            'values'    => $values,
            'errors'    => $errors,
            'user'      => $user,
            'skins'     => $this->skinModel->getSkins(true),
            'timezones' => $this->timezoneModel->getTimezones(true),
            'languages' => $this->languageModel->getLanguages(true),
            'roles'     => $this->role->getApplicationRoles(),
        ]));
    }

    /**
     * Save user information.
     */
    public function store()
    {
        $user = $this->getUser();
        $values = $this->request->getValues();

        if (!$this->userSession->isAdmin()) {
            if (isset($values['role'])) {
                unset($values['role']);
            }
        }

        list($valid, $errors) = $this->userValidator->validateModification($values);

        if ($valid) {
            if ($this->userModel->update($values)) {
                $this->flash->success(t('User updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update your user.'));
            }

            return $this->response->redirect($this->helper->url->to('UserViewController', 'show', ['user_id' => $user['id']]));
        }

        return $this->show($values, $errors);
    }
}
