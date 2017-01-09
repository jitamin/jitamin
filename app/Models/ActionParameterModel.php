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
 * Action Parameter Model.
 */
class ActionParameterModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'action_has_params';

    /**
     * Get all action params.
     *
     * @return array
     */
    public function getAll()
    {
        $params = $this->db->table(self::TABLE)->findAll();

        return $this->toDictionary($params);
    }

    /**
     * Get all params for a list of actions.
     *
     * @param array $action_ids
     *
     * @return array
     */
    public function getAllByActions(array $action_ids)
    {
        $params = $this->db->table(self::TABLE)->in('action_id', $action_ids)->findAll();

        return $this->toDictionary($params);
    }

    /**
     * Build params dictionary.
     *
     * @param array $params
     *
     * @return array
     */
    private function toDictionary(array $params)
    {
        $result = [];

        foreach ($params as $param) {
            $result[$param['action_id']][$param['name']] = $param['value'];
        }

        return $result;
    }

    /**
     * Get all action params for a given action.
     *
     * @param int $action_id
     *
     * @return array
     */
    public function getAllByAction($action_id)
    {
        return $this->db->hashtable(self::TABLE)->eq('action_id', $action_id)->getAll('name', 'value');
    }

    /**
     * Insert new parameters for an action.
     *
     * @param int   $action_id
     * @param array $values
     *
     * @return bool
     */
    public function create($action_id, array $values)
    {
        foreach ($values['params'] as $name => $value) {
            $param = [
                'action_id' => $action_id,
                'name'      => $name,
                'value'     => $value,
            ];

            if (!$this->db->table(self::TABLE)->save($param)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Duplicate action parameters.
     *
     * @param int   $project_id
     * @param int   $action_id
     * @param array $params
     *
     * @return bool
     */
    public function duplicateParameters($project_id, $action_id, array $params)
    {
        foreach ($params as $name => $value) {
            $value = $this->resolveParameter($project_id, $name, $value);

            if ($value === false) {
                $this->logger->error('ActionParameter::duplicateParameters => unable to resolve '.$name.'='.$value);

                return false;
            }

            $values = [
                'action_id' => $action_id,
                'name'      => $name,
                'value'     => $value,
            ];

            if (!$this->db->table(self::TABLE)->insert($values)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Resolve action parameter values according to another project.
     *
     * @param int    $project_id
     * @param string $name
     * @param string $value
     *
     * @return mixed
     */
    private function resolveParameter($project_id, $name, $value)
    {
        switch ($name) {
            case 'project_id':
                return $value != $project_id ? $value : false;
            case 'category_id':
                return $this->categoryModel->getIdByName($project_id, $this->categoryModel->getNameById($value)) ?: false;
            case 'src_column_id':
            case 'dest_column_id':
            case 'dst_column_id':
            case 'column_id':
                $column = $this->columnModel->getById($value);

                return empty($column) ? false : $this->columnModel->getColumnIdByTitle($project_id, $column['title']) ?: false;
            case 'user_id':
            case 'owner_id':
                return $this->projectPermissionModel->isAssignable($project_id, $value) ? $value : false;
            case 'swimlane_id':
                $column = $this->swimlaneModel->getById($value);

                return empty($column) ? false : $this->swimlaneModel->getIdByName($project_id, $column['name']) ?: false;
            default:
                return $value;
        }
    }
}
