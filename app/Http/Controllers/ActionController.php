<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

/**
 * Automatic Actions Controller.
 */
class ActionController extends BaseController
{
    /**
     * List of automatic actions for a given project.
     */
    public function index()
    {
        $project = $this->getProject();
        $actions = $this->actionModel->getAllByProject($project['id']);

        $this->response->html($this->helper->layout->project('action/index', [
            'values'            => ['project_id' => $project['id']],
            'project'           => $project,
            'actions'           => $actions,
            'available_actions' => $this->actionManager->getAvailableActions(),
            'available_events'  => $this->eventManager->getAll(),
            'available_params'  => $this->actionManager->getAvailableParameters($actions),
            'columns_list'      => $this->columnModel->getList($project['id']),
            'users_list'        => $this->projectUserRoleModel->getAssignableUsersList($project['id']),
            'projects_list'     => $this->projectUserRoleModel->getProjectsByUser($this->userSession->getId()),
            'colors_list'       => $this->colorModel->getList(),
            'categories_list'   => $this->categoryModel->getList($project['id']),
            'links_list'        => $this->linkModel->getList(0, false),
            'swimlane_list'     => $this->swimlaneModel->getList($project['id']),
            'title'             => t('Automatic actions'),
        ]));
    }

    /**
     * Show the form (step 1).
     */
    public function create()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('action/create', [
            'project'           => $project,
            'values'            => ['project_id' => $project['id']],
            'available_actions' => $this->actionManager->getAvailableActions(),
        ]));
    }

    /**
     * Choose the event according to the action (step 2).
     */
    public function event()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        if (empty($values['action_name']) || empty($values['project_id'])) {
            return $this->create();
        }

        return $this->response->html($this->template->render('action/event', [
            'values'            => $values,
            'project'           => $project,
            'available_actions' => $this->actionManager->getAvailableActions(),
            'events'            => $this->actionManager->getCompatibleEvents($values['action_name']),
        ]));
    }

    /**
     * Define action parameters (step 3).
     */
    public function params()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        if (empty($values['action_name']) || empty($values['project_id']) || empty($values['event_name'])) {
            return $this->create();
        }

        $action = $this->actionManager->getAction($values['action_name']);
        $action_params = $action->getActionRequiredParameters();

        if (empty($action_params)) {
            $this->doCreation($project, $values + ['params' => []]);
        }

        $projects_list = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());
        unset($projects_list[$project['id']]);

        return $this->response->html($this->template->render('action/params', [
            'values'            => $values,
            'action_params'     => $action_params,
            'columns_list'      => $this->columnModel->getList($project['id']),
            'users_list'        => $this->projectUserRoleModel->getAssignableUsersList($project['id']),
            'projects_list'     => $projects_list,
            'colors_list'       => $this->colorModel->getList(),
            'categories_list'   => $this->categoryModel->getList($project['id']),
            'links_list'        => $this->linkModel->getList(0, false),
            'priorities_list'   => $this->projectTaskPriorityModel->getPriorities($project),
            'project'           => $project,
            'available_actions' => $this->actionManager->getAvailableActions(),
            'swimlane_list'     => $this->swimlaneModel->getList($project['id']),
            'events'            => $this->actionManager->getCompatibleEvents($values['action_name']),
        ]));
    }

    /**
     * Save the action (last step).
     */
    public function store()
    {
        $this->doCreation($this->getProject(), $this->request->getValues());
    }

    /**
     * Common method to save the action.
     *
     * @param array $project Project properties
     * @param array $values  Form values
     */
    private function doCreation(array $project, array $values)
    {
        list($valid) = $this->actionValidator->validateCreation($values);

        if ($valid) {
            if ($this->actionModel->create($values) !== false) {
                $this->flash->success(t('Your automatic action have been created successfully.'));
            } else {
                $this->flash->failure(t('Unable to create your automatic action.'));
            }
        }

        $this->response->redirect($this->helper->url->to('ActionController', 'index', ['project_id' => $project['id']]));
    }

    /**
     * Confirmation dialog before removing an action.
     */
    public function confirm()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('action/remove', [
            'action'            => $this->actionModel->getById($this->request->getIntegerParam('action_id')),
            'available_events'  => $this->eventManager->getAll(),
            'available_actions' => $this->actionManager->getAvailableActions(),
            'project'           => $project,
            'title'             => t('Remove an action'),
        ]));
    }

    /**
     * Remove an action.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $project = $this->getProject();
        $action = $this->actionModel->getById($this->request->getIntegerParam('action_id'));

        if (!empty($action) && $this->actionModel->remove($action['id'])) {
            $this->flash->success(t('Action removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this action.'));
        }

        $this->response->redirect($this->helper->url->to('ActionController', 'index', ['project_id' => $project['id']]));
    }
}
