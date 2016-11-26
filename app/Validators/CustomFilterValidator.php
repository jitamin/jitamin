<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Validator;

use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Custom Filter Validator
 */
class CustomFilterValidator extends BaseValidator
{
    /**
     * Common validation rules
     *
     * @access private
     * @return array
     */
    private function commonValidationRules()
    {
        return [
            new Validators\Required('project_id', t('Field required')),
            new Validators\Required('user_id', t('Field required')),
            new Validators\Required('name', t('Field required')),
            new Validators\Required('filter', t('Field required')),
            new Validators\Integer('user_id', t('This value must be an integer')),
            new Validators\Integer('project_id', t('This value must be an integer')),
            new Validators\MaxLength('name', t('The maximum length is %d characters', 100), 100),
            new Validators\MaxLength('filter', t('The maximum length is %d characters', 100), 100)
        ];
    }

    /**
     * Validate filter creation
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, $this->commonValidationRules());

        return [
            $v->execute(),
            $v->getErrors()
        ];
    }

    /**
     * Validate filter modification
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateModification(array $values)
    {
        $rules = [
            new Validators\Required('id', t('Field required')),
            new Validators\Integer('id', t('This value must be an integer')),
        ];

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return [
            $v->execute(),
            $v->getErrors()
        ];
    }
}
