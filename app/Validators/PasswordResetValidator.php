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
use Gregwar\Captcha\CaptchaBuilder;

/**
 * Password Reset Validator
 */
class PasswordResetValidator extends BaseValidator
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
        return $this->executeValidators(['validateFields', 'validateCaptcha'], $values);
    }

    /**
     * Validate modification
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateModification(array $values)
    {
        $v = new Validator($values, $this->commonPasswordValidationRules());

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }

    /**
     * Validate fields
     *
     * @access protected
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    protected function validateFields(array $values)
    {
        $v = new Validator($values, [
            new Validators\Required('captcha', t('This value is required')),
            new Validators\Required('username', t('The username is required')),
            new Validators\MaxLength('username', t('The maximum length is %d characters', 50), 50),
        ]);

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }

    /**
     * Validate captcha
     *
     * @access protected
     * @param  array   $values           Form values
     * @return boolean
     */
    protected function validateCaptcha(array $values)
    {
        $errors = [];

        if (! isset($this->sessionStorage->captcha)) {
            $result = false;
        } else {
            $builder = new CaptchaBuilder;
            $builder->setPhrase($this->sessionStorage->captcha);
            $result = $builder->testPhrase(isset($values['captcha']) ? $values['captcha'] : '');

            if (! $result) {
                $errors['captcha'] = [t('Invalid captcha')];
            }
        }

        return [$result, $errors];
    }
}
