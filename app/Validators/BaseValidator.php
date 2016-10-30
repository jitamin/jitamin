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

use Hiject\Core\Base;
use SimpleValidator\Validators;

/**
 * Base Validator
 */
abstract class BaseValidator extends Base
{
    /**
     * Execute multiple validators
     *
     * @access public
     * @param  array  $validators       List of validators
     * @param  array  $values           Form values
     * @return array  $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function executeValidators(array $validators, array $values)
    {
        $result = false;
        $errors = array();

        foreach ($validators as $method) {
            list($result, $errors) = $this->$method($values);

            if (! $result) {
                break;
            }
        }

        return array($result, $errors);
    }

    /**
     * Common password validation rules
     *
     * @access protected
     * @return array
     */
    protected function commonPasswordValidationRules()
    {
        return array(
            new Validators\Required('password', t('The password is required')),
            new Validators\MinLength('password', t('The minimum length is %d characters', 6), 6),
            new Validators\Required('confirmation', t('The confirmation is required')),
            new Validators\Equals('password', 'confirmation', t('Passwords don\'t match')),
        );
    }
}
