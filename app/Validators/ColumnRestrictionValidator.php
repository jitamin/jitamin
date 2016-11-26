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
 * Class ColumnRestrictionValidator
 */
class ColumnRestrictionValidator extends BaseValidator
{
    /**
     * Validate creation
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, [
            new Validators\Required('project_id', t('This field is required')),
            new Validators\Integer('project_id', t('This value must be an integer')),
            new Validators\Required('role_id', t('This field is required')),
            new Validators\Integer('role_id', t('This value must be an integer')),
            new Validators\Required('rule', t('This field is required')),
            new Validators\Required('column_id', t('This field is required')),
            new Validators\Integer('column_id', t('This value must be an integer')),
        ]);

        return [
            $v->execute(),
            $v->getErrors()
        ];
    }
}
