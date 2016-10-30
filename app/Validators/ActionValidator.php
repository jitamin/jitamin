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
 * Action Validator
 */
class ActionValidator extends BaseValidator
{
    /**
     * Validate action creation
     *
     * @access public
     * @param  array   $values           Required parameters to save an action
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, array(
            new Validators\Required('project_id', t('The project id is required')),
            new Validators\Integer('project_id', t('This value must be an integer')),
            new Validators\Required('event_name', t('This value is required')),
            new Validators\Required('action_name', t('This value is required')),
            new Validators\Required('params', t('This value is required')),
        ));

        return array(
            $v->execute(),
            $v->getErrors()
        );
    }
}
