<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Validator;

use Jitamin\Model\TaskModel;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Task Link Validator.
 */
class TaskLinkValidator extends BaseValidator
{
    /**
     * Common validation rules.
     *
     * @return array
     */
    private function commonValidationRules()
    {
        return [
            new Validators\Required('task_id', t('Field required')),
            new Validators\Required('opposite_task_id', t('Field required')),
            new Validators\Required('link_id', t('Field required')),
            new Validators\NotEquals('opposite_task_id', 'task_id', t('A task cannot be linked to itself')),
            new Validators\Exists('opposite_task_id', t('This linked task id doesn\'t exists'), $this->db->getConnection(), TaskModel::TABLE, 'id'),
        ];
    }

    /**
     * Validate creation.
     *
     * @param array $values Form values
     *
     * @return array $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, $this->commonValidationRules());

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }

    /**
     * Validate modification.
     *
     * @param array $values Form values
     *
     * @return array $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateModification(array $values)
    {
        $rules = [
            new Validators\Required('id', t('Field required')),
        ];

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }
}
