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
 * Currency Validator.
 */
class CurrencyValidator extends BaseValidator
{
    /**
     * Validate.
     *
     * @param array $values Form values
     *
     * @return array $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, [
            new Validators\Required('currency', t('Field required')),
            new Validators\Required('rate', t('Field required')),
            new Validators\Numeric('rate', t('This value must be numeric')),
        ]);

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }
}
