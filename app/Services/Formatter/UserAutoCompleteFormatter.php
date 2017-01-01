<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Core\Filter\FormatterInterface;
use Jitamin\Model\UserModel;

/**
 * Auto-complete formatter for user filter.
 */
class UserAutoCompleteFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Format the tasks for the ajax autocompletion.
     *
     * @return array
     */
    public function format()
    {
        $users = $this->query->columns(UserModel::TABLE.'.id', UserModel::TABLE.'.username', UserModel::TABLE.'.name')->findAll();

        foreach ($users as &$user) {
            if (empty($user['name'])) {
                $user['value'] = $user['username'].' (#'.$user['id'].')';
                $user['label'] = $user['username'];
            } else {
                $user['value'] = $user['name'].' (#'.$user['id'].')';
                $user['label'] = $user['name'].' ('.$user['username'].')';
            }
        }

        return $users;
    }
}
