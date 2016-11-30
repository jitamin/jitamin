<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Import;

use Hiject\Core\Base;
use Hiject\Core\Csv;
use Hiject\Core\Security\Role;
use Hiject\Model\UserModel;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * User Import.
 */
class UserImport extends Base
{
    /**
     * Number of successful import.
     *
     * @var int
     */
    public $counter = 0;

    /**
     * Get mapping between CSV header and SQL columns.
     *
     * @return array
     */
    public function getColumnMapping()
    {
        return [
            'username'         => 'Username',
            'password'         => 'Password',
            'email'            => 'Email',
            'name'             => 'Full Name',
            'is_admin'         => 'Administrator',
            'is_manager'       => 'Manager',
            'is_ldap_user'     => 'Remote User',
        ];
    }

    /**
     * Import a single row.
     *
     * @param array $row
     * @param int   $line_number
     */
    public function import(array $row, $line_number)
    {
        $row = $this->prepare($row);

        if ($this->validateCreation($row)) {
            if ($this->userModel->create($row) !== false) {
                $this->logger->debug('UserImport: imported successfully line '.$line_number);
                $this->counter++;
            } else {
                $this->logger->error('UserImport: creation error at line '.$line_number);
            }
        } else {
            $this->logger->error('UserImport: validation error at line '.$line_number);
        }
    }

    /**
     * Format row before validation.
     *
     * @param array $row
     *
     * @return array
     */
    public function prepare(array $row)
    {
        $row['username'] = strtolower($row['username']);

        foreach (['is_admin', 'is_manager', 'is_ldap_user'] as $field) {
            $row[$field] = Csv::getBooleanValue($row[$field]);
        }

        if ($row['is_admin'] == 1) {
            $row['role'] = Role::APP_ADMIN;
        } elseif ($row['is_manager'] == 1) {
            $row['role'] = Role::APP_MANAGER;
        } else {
            $row['role'] = Role::APP_USER;
        }

        unset($row['is_admin']);
        unset($row['is_manager']);

        $this->helper->model->removeEmptyFields($row, ['password', 'email', 'name']);

        return $row;
    }

    /**
     * Validate user creation.
     *
     * @param array $values
     *
     * @return bool
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, [
            new Validators\MaxLength('username', t('The maximum length is %d characters', 50), 50),
            new Validators\Unique('username', t('The username must be unique'), $this->db->getConnection(), UserModel::TABLE, 'id'),
            new Validators\MinLength('password', t('The minimum length is %d characters', 6), 6),
            new Validators\Email('email', t('Email address invalid')),
            new Validators\Integer('is_ldap_user', t('This value must be an integer')),
        ]);

        return $v->execute();
    }
}
