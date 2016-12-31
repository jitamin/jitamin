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

use Jitamin\Model\GroupModel;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Group Validator.
 */
class GroupValidator extends BaseValidator
{
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
            new Validators\Required('id', t('The id is required')),
        ];

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }

    /**
     * Common validation rules.
     *
     * @return array
     */
    private function commonValidationRules()
    {
        return [
            new Validators\Required('name', t('The name is required')),
            new Validators\MaxLength('name', t('The maximum length is %d characters', 100), 100),
            new Validators\Unique('name', t('The name must be unique'), $this->db->getConnection(), GroupModel::TABLE, 'id'),
            new Validators\MaxLength('external_id', t('The maximum length is %d characters', 255), 255),
            new Validators\Integer('id', t('This value must be an integer')),
        ];
    }
}
