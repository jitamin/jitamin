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

use Jitamin\Formatter\GroupAutoCompleteFormatter;
use Jitamin\Http\Controllers\Controller;

/**
 * Group Controller.
 */
class GroupController extends Controller
{
    /**
     * List all groups.
     */
    public function index()
    {
        $paginator = $this->paginator
            ->setUrl('Admin/GroupController', 'index')
            ->setMax(30)
            ->setOrder('name')
            ->setQuery($this->groupModel->getQuery())
            ->calculate();

        $this->response->html($this->helper->layout->admin('admin/group/index', [
            'title'     => t('Admin').' &raquo; '.t('Groups management'),
            'paginator' => $paginator,
        ], 'admin/group/subside'));
    }

    /**
     * Display a form to create a new group.
     *
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = [], array $errors = [])
    {
        $this->response->html($this->template->render('admin/group/create', [
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

                return $this->response->redirect($this->helper->url->to('Admin/GroupController', 'index'), true);
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

        $this->response->html($this->template->render('admin/group/edit', [
            'errors' => $errors,
            'values' => $values,
        ]));
    }

    /**
     * Validate and update a group.
     */
    public function update()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->groupValidator->validateModification($values);

        if ($valid) {
            if ($this->groupModel->update($values) !== false) {
                $this->flash->success(t('Group updated successfully.'));

                return $this->response->redirect($this->helper->url->to('Admin/GroupController', 'index'), true);
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
            ->setUrl('Admin/GroupController', 'users', ['group_id' => $group_id])
            ->setMax(30)
            ->setOrder('username')
            ->setQuery($this->groupMemberModel->getQuery($group_id))
            ->calculate();

        $this->response->html($this->helper->layout->admin('admin/group/users', [
            'title'     => t('Members of %s', $group['name']).' ('.$paginator->getTotal().')',
            'paginator' => $paginator,
            'group'     => $group,
        ], 'admin/group/subside'));
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

        $this->response->html($this->template->render('admin/group/associate', [
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

                return $this->response->redirect($this->helper->url->to('Admin/GroupController', 'users', ['group_id' => $values['group_id']]), true);
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

        $this->response->html($this->template->render('admin/group/dissociate', [
            'group' => $group,
            'user'  => $user,
        ]));
    }

    /**
     * Remove a user from a group.
     */
    public function removeUser()
    {
        $group_id = $this->request->getIntegerParam('group_id');
        $user_id = $this->request->getIntegerParam('user_id');

        if ($this->groupMemberModel->removeUser($group_id, $user_id)) {
            $this->flash->success(t('User removed successfully from this group.'));
        } else {
            $this->flash->failure(t('Unable to remove this user from the group.'));
        }

        $this->response->redirect($this->helper->url->to('Admin/GroupController', 'users', ['group_id' => $group_id]), true);
    }

    /**
     * Remove a group.
     */
    public function remove()
    {
        $group_id = $this->request->getIntegerParam('group_id');

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->groupModel->remove($group_id)) {
                $this->flash->success(t('Group removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this group.'));
            }

            return $this->response->redirect($this->helper->url->to('Admin/GroupController', 'index'), true);
        }

        $group = $this->groupModel->getById($group_id);

        return $this->response->html($this->template->render('admin/group/remove', [
            'group' => $group,
        ]));
    }

    /**
     * Group auto-completion (Ajax).
     */
    public function autocompleteAjax()
    {
        $search = $this->request->getStringParam('term');
        $formatter = new GroupAutoCompleteFormatter($this->groupManager->find($search));
        $this->response->json($formatter->format());
    }
}
