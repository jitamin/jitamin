<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Formatter;

use Hiject\Model\UserModel;
use Hiject\Core\Filter\FormatterInterface;

/**
 * Auto-complete formatter for user filter
 */
class UserAutoCompleteFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Format the tasks for the ajax autocompletion
     *
     * @access public
     * @return array
     */
    public function format()
    {
        $users = $this->query->columns(UserModel::TABLE.'.id', UserModel::TABLE.'.username', UserModel::TABLE.'.name')->findAll();

        foreach ($users as &$user) {
            $user['value'] = $user['username'].' (#'.$user['id'].')';

            if (empty($user['name'])) {
                $user['label'] = $user['username'];
            } else {
                $user['label'] = $user['name'].' ('.$user['username'].')';
            }
        }

        return $users;
    }
}
