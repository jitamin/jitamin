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

use Hiject\Model\UserModel;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * User Validator.
 */
class UserValidator extends BaseValidator
{
    /**
     * Common validation rules.
     *
     * @return array
     */
    protected function commonValidationRules()
    {
        return [
            new Validators\MaxLength('role', t('The maximum length is %d characters', 25), 25),
            new Validators\MaxLength('username', t('The maximum length is %d characters', 50), 50),
            new Validators\Unique('username', t('The username must be unique'), $this->db->getConnection(), UserModel::TABLE, 'id'),
            new Validators\Email('email', t('Email address invalid')),
            new Validators\Unique('email', t('The email must be unique'), $this->db->getConnection(), UserModel::TABLE, 'id'),
            new Validators\Integer('is_ldap_user', t('This value must be an integer')),
        ];
    }

    /**
     * Validate user creation.
     *
     * @param array $values Form values
     *
     * @return array $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $rules = [
            new Validators\Required('username', t('The username is required')),
        ];

        if (isset($values['is_ldap_user']) && $values['is_ldap_user'] == 1) {
            $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));
        } else {
            $v = new Validator($values, array_merge($rules, $this->commonValidationRules(), $this->commonPasswordValidationRules()));
        }

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }

    /**
     * Validate user modification.
     *
     * @param array $values Form values
     *
     * @return array $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateModification(array $values)
    {
        $rules = [
            new Validators\Required('id', t('The user id is required')),
            new Validators\Required('username', t('The username is required')),
        ];

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }

    /**
     * Validate user API modification.
     *
     * @param array $values Form values
     *
     * @return array $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateApiModification(array $values)
    {
        $rules = [
            new Validators\Required('id', t('The user id is required')),
        ];

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }

    /**
     * Validate password modification.
     *
     * @param array $values Form values
     *
     * @return array $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validatePasswordModification(array $values)
    {
        $rules = [
            new Validators\Required('id', t('The user id is required')),
            new Validators\Required('current_password', t('The current password is required')),
        ];

        $v = new Validator($values, array_merge($rules, $this->commonPasswordValidationRules()));

        if ($v->execute()) {
            if ($this->authenticationManager->passwordAuthentication($this->userSession->getUsername(), $values['current_password'], false)) {
                return [true, []];
            } else {
                return [false, ['current_password' => [t('Wrong password')]]];
            }
        }

        return [false, $v->getErrors()];
    }
}
