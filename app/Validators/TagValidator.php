<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Validator;

use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Tag Validator.
 */
class TagValidator extends BaseValidator
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
        $result = $v->execute();
        $errors = $v->getErrors();

        if ($result && $this->tagModel->exists($values['project_id'], $values['name'])) {
            $result = false;
            $errors = ['name' => [t('The name must be unique')]];
        }

        return [$result, $errors];
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
        $result = $v->execute();
        $errors = $v->getErrors();

        if ($result && $this->tagModel->exists($values['project_id'], $values['name'], $values['id'])) {
            $result = false;
            $errors = ['name' => [t('The name must be unique')]];
        }

        return [$result, $errors];
    }

    /**
     * Common validation rules.
     *
     * @return array
     */
    protected function commonValidationRules()
    {
        return [
            new Validators\Required('project_id', t('Field required')),
            new Validators\Required('name', t('Field required')),
            new Validators\MaxLength('name', t('The maximum length is %d characters', 255), 255),
        ];
    }
}
