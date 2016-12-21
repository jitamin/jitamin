<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

/**
 * Project Metadata.
 */
class ProjectMetadataModel extends MetadataModel
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
        return 'project_has_metadata';
    }

    /**
     * Define the entity key.
     *
     * @return string
     */
    protected function getEntityKey()
    {
        return 'project_id';
    }

    /**
     * Helper method to duplicate all metadata to another project.
     *
     * @param int $src_project_id
     * @param int $dst_project_id
     *
     * @return bool
     */
    public function duplicate($src_project_id, $dst_project_id)
    {
        $metadata = $this->getAll($src_project_id);

        if (!$this->save($dst_project_id, $metadata)) {
            return false;
        }

        return true;
    }
}
