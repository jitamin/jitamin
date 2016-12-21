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
 * Task File Model.
 */
class TaskFileModel extends FileModel
{
    /**
     * Table name.
     *
     * @var string
     */
    const TABLE = 'task_has_files';

    /**
     * Events.
     *
     * @var string
     */
    const EVENT_CREATE = 'task.file.create';

    /**
     * Get the table.
     *
     * @abstract
     *
     * @return string
     */
    protected function getTable()
    {
        return self::TABLE;
    }

    /**
     * Define the foreign key.
     *
     * @abstract
     *
     * @return string
     */
    protected function getForeignKey()
    {
        return 'task_id';
    }

    /**
     * Define the path prefix.
     *
     * @abstract
     *
     * @return string
     */
    protected function getPathPrefix()
    {
        return 'tasks';
    }

    /**
     * Get projectId from fileId.
     *
     * @param int $file_id
     *
     * @return int
     */
    public function getProjectId($file_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq(self::TABLE.'.id', $file_id)
            ->join(TaskModel::TABLE, 'id', 'task_id')
            ->findOneColumn(TaskModel::TABLE.'.project_id') ?: 0;
    }

    /**
     * Handle screenshot upload.
     *
     * @param int    $task_id Task id
     * @param string $blob    Base64 encoded image
     *
     * @return bool|int
     */
    public function uploadScreenshot($task_id, $blob)
    {
        $original_filename = e('Screenshot taken %s', $this->helper->dt->datetime(time())).'.png';

        return $this->uploadContent($task_id, $original_filename, $blob);
    }

    /**
     * Fire file creation event.
     *
     * @param int $file_id
     */
    protected function fireCreationEvent($file_id)
    {
        $this->queueManager->push($this->taskFileEventJob->withParams($file_id, self::EVENT_CREATE));
    }
}
