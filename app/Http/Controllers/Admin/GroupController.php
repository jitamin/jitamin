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
 * Group Controller.
 */
class GroupController extends BaseController
{
    /**
     * List all groups.
     */
    public function index()
    {
        $paginator = $this->paginator
            ->setUrl('GroupController', 'index')
            ->setMax(30)
            ->setOrder('name')
            ->setQuery($this->groupModel->getQuery())
            ->calculate();

        $this->response->html($this->helper->layout->app('group/index', [
            'title'     => t('Groups').' ('.$paginator->getTotal().')',
            'paginator' => $paginator,
        ]));
    }

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

                return $this->response->redirect($this->helper->url->to('GroupController', 'index'), true);
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

                return $this->response->redirect($this->helper->url->to('GroupController', 'index'), true);
            } else {
                $this->flash->failure(t('Unable to update your group.'));
            }
        }

        return $this->show($values, $errors);
    }

    /**
     * List all users.
     */
    public function users()
    {
        $group_id = $this->request->getIntegerParam('group_id');
        $group = $this->groupModel->getById($group_id);

        $paginator = $this->paginator
            ->setUrl('GroupController', 'users', ['group_id' => $group_id])
            ->setMax(30)
            ->setOrder('username')
            ->setQuery($this->groupMemberModel->getQuery($group_id))
            ->calculate();

        $this->response->html($this->helper->layout->app('group/users', [
            'title'     => t('Members of %s', $group['name']).' ('.$paginator->getTotal().')',
            'paginator' => $paginator,
            'group'     => $group,
        ]));
    }

    /**
     * Form to associate a user to a group.
     *
     * @param array $values
     * @param array $errors
     */
    public function associate(array $values = [], array $errors = [])
    {
        $group_id = $this->request->getIntegerParam('group_id');
        $group = $this->groupModel->getById($group_id);

        if (empty($values)) {
            $values['group_id'] = $group_id;
        }

        $this->response->html($this->template->render('group/associate', [
            'users'  => $this->userModel->prepareList($this->groupMemberModel->getNotMembers($group_id)),
            'group'  => $group,
            'errors' => $errors,
            'values' => $values,
        ]));
    }

    /**
     * Add user to a group.
     */
    public function addUser()
    {
        $values = $this->request->getValues();

        if (isset($values['group_id']) && isset($values['user_id'])) {
            if ($this->groupMemberModel->addUser($values['group_id'], $values['user_id'])) {
                $this->flash->success(t('Group member added successfully.'));

                return $this->response->redirect($this->helper->url->to('GroupController', 'users', ['group_id' => $values['group_id']]), true);
            } else {
                $this->flash->failure(t('Unable to add group member.'));
            }
        }

        return $this->associate($values);
    }

    /**
     * Confirmation dialog to remove a user from a group.
     */
    public function dissociate()
    {
        $group_id = $this->request->getIntegerParam('group_id');
        $user_id = $this->request->getIntegerParam('user_id');
        $group = $this->groupModel->getById($group_id);
        $user = $this->userModel->getById($user_id);

        $this->response->html($this->template->render('group/dissociate', [
            'group' => $group,
            'user'  => $user,
        ]));
    }

    /**
     * Remove a user from a group.
     */
    public function removeUser()
    {
        $this->checkCSRFParam();
        $group_id = $this->request->getIntegerParam('group_id');
        $user_id = $this->request->getIntegerParam('user_id');

        if ($this->groupMemberModel->removeUser($group_id, $user_id)) {
            $this->flash->success(t('User removed successfully from this group.'));
        } else {
            $this->flash->failure(t('Unable to remove this user from the group.'));
        }

        $this->response->redirect($this->helper->url->to('GroupController', 'users', ['group_id' => $group_id]), true);
    }

    /**
     * Confirmation dialog to remove a group.
     */
    public function confirm()
    {
        $group_id = $this->request->getIntegerParam('group_id');
        $group = $this->groupModel->getById($group_id);

        $this->response->html($this->template->render('group/remove', [
            'group' => $group,
        ]));
    }

    /**
     * Remove a group.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $group_id = $this->request->getIntegerParam('group_id');

        if ($this->groupModel->remove($group_id)) {
            $this->flash->success(t('Group removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this group.'));
        }

        $this->response->redirect($this->helper->url->to('GroupController', 'index'), true);
    }
}
