<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Foundation\Database\Model;

/**
 * Action Model.
 */
class ActionModel extends Model
{
    /**
     * SQL table name for actions.
     *
     * @var string
     */
    const TABLE = 'actions';

    /**
     * Return actions and parameters for a given user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getAllByUser($user_id)
    {
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($user_id);
        $actions = [];

        if (!empty($project_ids)) {
            $actions = $this->db->table(self::TABLE)->in('project_id', $project_ids)->findAll();
            $params = $this->actionParameterModel->getAllByActions(array_column($actions, 'id'));
            $this->attachParamsToActions($actions, $params);
        }

        return $actions;
    }

    /**
     * Return actions and parameters for a given project.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $actions = $this->db->table(self::TABLE)->eq('project_id', $project_id)->findAll();
        $params = $this->actionParameterModel->getAllByActions(array_column($actions, 'id'));

        return $this->attachParamsToActions($actions, $params);
    }

    /**
     * Return all actions and parameters.
     *
     * @return array
     */
    public function getAll()
    {
        $actions = $this->db->table(self::TABLE)->findAll();
        $params = $this->actionParameterModel->getAll();

        return $this->attachParamsToActions($actions, $params);
    }

    /**
     * Fetch an action.
     *
     * @param int $action_id
     *
     * @return array
     */
    public function getById($action_id)
    {
        $action = $this->db->table(self::TABLE)->eq('id', $action_id)->findOne();

        if (!empty($action)) {
            $action['params'] = $this->actionParameterModel->getAllByAction($action_id);
        }

        return $action;
    }

    /**
     * Get the projectId by the actionId.
     *
     * @param int $action_id
     *
     * @return int
     */
    public function getProjectId($action_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $action_id)->findOneColumn('project_id') ?: 0;
    }

    /**
     * Attach parameters to actions.
     *
     * @param array $actions
     * @param array $params
     *
     * @return array
     */
    private function attachParamsToActions(array &$actions, array &$params)
    {
        foreach ($actions as &$action) {
            $action['params'] = isset($params[$action['id']]) ? $params[$action['id']] : [];
        }

        return $actions;
    }

    /**
     * Remove an action.
     *
     * @param int $action_id
     *
     * @return bool
     */
    public function remove($action_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $action_id)->remove();
    }

    /**
     * Create an action.
     *
     * @param array $values Required parameters to save an action
     *
     * @return bool|int
     */
    public function create(array $values)
    {
        $this->db->startTransaction();

        $action = [
            'project_id'  => $values['project_id'],
            'event_name'  => $values['event_name'],
            'action_name' => $values['action_name'],
        ];

        if (!$this->db->table(self::TABLE)->insert($action)) {
            $this->db->cancelTransaction();

            return false;
        }

        $action_id = $this->db->getLastId();

        if (!$this->actionParameterModel->create($action_id, $values)) {
            $this->db->cancelTransaction();

            return false;
        }

        $this->db->closeTransaction();

        return $action_id;
    }

    /**
     * Copy actions from a project to another one (skip actions that cannot resolve parameters).
     *
     * @author Antonio Rabelo
     *
     * @param int $src_project_id Source project id
     * @param int $dst_project_id Destination project id
     *
     * @return bool
     */
    public function duplicate($src_project_id, $dst_project_id)
    {
        $actions = $this->actionModel->getAllByProject($src_project_id);

        foreach ($actions as $action) {
            $this->db->startTransaction();

            $values = [
                'project_id'  => $dst_project_id,
                'event_name'  => $action['event_name'],
                'action_name' => $action['action_name'],
            ];

            if (!$this->db->table(self::TABLE)->insert($values)) {
                $this->db->cancelTransaction();
                continue;
            }

            $action_id = $this->db->getLastId();

            if (!$this->actionParameterModel->duplicateParameters($dst_project_id, $action_id, $action['params'])) {
                $this->logger->error('Action::duplicate => skip action '.$action['action_name'].' '.$action['id']);
                $this->db->cancelTransaction();
                continue;
            }

            $this->db->closeTransaction();
        }

        return true;
    }
}
