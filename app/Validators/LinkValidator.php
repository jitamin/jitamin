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

use Jitamin\Model\LinkModel;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Link Validator.
 */
class LinkValidator extends BaseValidator
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
        $v = new Validator($values, [
            new Validators\Required('label', t('Field required')),
            new Validators\Unique('label', t('This label must be unique'), $this->db->getConnection(), LinkModel::TABLE),
            new Validators\NotEquals('label', 'opposite_label', t('The labels must be different')),
        ]);

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
        $v = new Validator($values, [
            new Validators\Required('id', t('Field required')),
            new Validators\Required('opposite_id', t('Field required')),
            new Validators\Required('label', t('Field required')),
            new Validators\Unique('label', t('This label must be unique'), $this->db->getConnection(), LinkModel::TABLE),
        ]);

        return [
            $v->execute(),
            $v->getErrors(),
        ];
    }
}
