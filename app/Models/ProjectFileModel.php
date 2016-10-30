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
 * Project File Model
 */
class ProjectFileModel extends FileModel
{
    /**
     * Table name
     *
     * @var string
     */
    const TABLE = 'project_has_files';

    /**
     * Events
     *
     * @var string
     */
    const EVENT_CREATE = 'project.file.create';

    /**
     * Get the table
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getTable()
    {
        return self::TABLE;
    }

    /**
     * Define the foreign key
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getForeignKey()
    {
        return 'project_id';
    }

    /**
     * Define the path prefix
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getPathPrefix()
    {
        return 'projects';
    }

    /**
     * Fire file creation event
     *
     * @access protected
     * @param  integer $file_id
     */
    protected function fireCreationEvent($file_id)
    {
        $this->queueManager->push($this->projectFileEventJob->withParams($file_id, self::EVENT_CREATE));
    }
}
