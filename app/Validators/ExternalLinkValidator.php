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
 * External Link Validator.
 */
class ExternalLinkValidator extends BaseValidator
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
            new Validators\Required('url', t('Field required')),
            new Validators\MaxLength('url', t('The maximum length is %d characters', 255), 255),
            new Validators\Required('title', t('Field required')),
            new Validators\MaxLength('title', t('The maximum length is %d characters', 255), 255),
            new Validators\Required('link_type', t('Field required')),
            new Validators\MaxLength('link_type', t('The maximum length is %d characters', 100), 100),
            new Validators\Required('dependency', t('Field required')),
            new Validators\MaxLength('dependency', t('The maximum length is %d characters', 100), 100),
            new Validators\Integer('id', t('This value must be an integer')),
            new Validators\Required('task_id', t('Field required')),
            new Validators\Integer('task_id', t('This value must be an integer')),
        ];
    }
}
