<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

/**
 * User Metadata
 */
class UserMetadataModel extends MetadataModel
{
    const KEY_COMMENT_SORTING_DIRECTION = 'comment.sorting.direction';
    const KEY_BOARD_COLLAPSED = 'board.collapsed.';

    /**
     * Get the table
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getTable()
    {
        return 'user_has_metadata';
    }

    /**
     * Define the entity key
     *
     * @access protected
     * @return string
     */
    protected function getEntityKey()
    {
        return 'user_id';
    }
}
