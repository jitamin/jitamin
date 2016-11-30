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

use Hiject\Core\Security\Role;
use Hiject\Notification\MailNotification;

/**
 * Class UserCreationController.
 */
class UserCreationController extends BaseController
{
    /**
     * Display a form to create a new user.
     *
     * @param array $values
     * @param array $errors
     */
    public function show(array $values = [], array $errors = [])
    {
        $isRemote = $this->request->getIntegerParam('remote') == 1 || (isset($values['is_ldap_user']) && $values['is_ldap_user'] == 1);
        $template = $isRemote ? 'user_creation/remote' : 'user_creation/local';

        $this->response->html($this->template->render($template, [
            'timezones' => $this->timezoneModel->getTimezones(true),
            'languages' => $this->languageModel->getLanguages(true),
            'roles'     => $this->role->getApplicationRoles(),
            'projects'  => $this->projectModel->getList(),
            'errors'    => $errors,
            'values'    => $values + ['role' => Role::APP_USER],
        ]));
    }

    /**
     * Validate and save a new user.
     */
    public function save()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->userValidator->validateCreation($values);

        if ($valid) {
            $this->createUser($values);
        } else {
            $this->show($values, $errors);
        }
    }

    /**
     * Create user.
     *
     * @param array $values
     */
    private function createUser(array $values)
    {
        $project_id = empty($values['project_id']) ? 0 : $values['project_id'];
        unset($values['project_id']);

        $user_id = $this->userModel->create($values);

        if ($user_id !== false) {
            if ($project_id !== 0) {
                $this->projectUserRoleModel->addUser($project_id, $user_id, Role::PROJECT_MEMBER);
            }

            if (!empty($values['notifications_enabled'])) {
                $this->userNotificationTypeModel->saveSelectedTypes($user_id, [MailNotification::TYPE]);
            }

            $this->flash->success(t('User created successfully.'));
            $this->response->redirect($this->helper->url->to('UserViewController', 'show', ['user_id' => $user_id]));
        } else {
            $this->flash->failure(t('Unable to create your user.'));
            $this->response->redirect($this->helper->url->to('UserListController', 'show'));
        }
    }
}
