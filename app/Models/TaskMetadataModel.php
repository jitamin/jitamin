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
 * Task Metadata.
 */
class TaskMetadataModel extends MetadataModel
{
    /**
     * Get the table.
     *
     * @abstract
     *
     * @return string
     */
    protected function getTable()
    {
        return 'task_has_metadata';
    }

    /**
     * Define the entity key.
     *
     * @return string
     */
    protected function getEntityKey()
    {
        return 'task_id';
    }
}
