<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Foundation\Database\Model;

/**
 * Tag Duplication.
 */
class TagDuplicationModel extends Model
{
    /**
     * Duplicate project tags to another project.
     *
     * @param int $src_project_id
     * @param int $dst_project_id
     *
     * @return bool
     */
    public function duplicate($src_project_id, $dst_project_id)
    {
        $tags = $this->tagModel->getAllByProject($src_project_id);
        $results = [];

        foreach ($tags as $tag) {
            $results[] = $this->tagModel->create($dst_project_id, $tag['name']);
        }

        return !in_array(false, $results, true);
    }

    /**
     * Link tags to the new tasks.
     *
     * @param int $src_task_id
     * @param int $dst_task_id
     * @param int $dst_project_id
     */
    public function duplicateTaskTagsToAnotherProject($src_task_id, $dst_task_id, $dst_project_id)
    {
        $tags = $this->taskTagModel->getTagsByTask($src_task_id);

        foreach ($tags as $tag) {
            $tag_id = $this->tagModel->getIdByName($dst_project_id, $tag['name']);

            if ($tag_id) {
                $this->taskTagModel->associateTag($dst_task_id, $tag_id);
            }
        }
    }

    /**
     * Duplicate tags to the new task.
     *
     * @param int $src_task_id
     * @param int $dst_task_id
     */
    public function duplicateTaskTags($src_task_id, $dst_task_id)
    {
        $tags = $this->taskTagModel->getTagsByTask($src_task_id);

        foreach ($tags as $tag) {
            $this->taskTagModel->associateTag($dst_task_id, $tag['id']);
        }
    }

    /**
     * Remove tags that are not available in destination project.
     *
     * @param int $task_id
     * @param int $dst_project_id
     */
    public function syncTaskTagsToAnotherProject($task_id, $dst_project_id)
    {
        $tag_ids = $this->taskTagModel->getTagIdsByTaskNotAvailableInProject($task_id, $dst_project_id);

        foreach ($tag_ids as $tag_id) {
            $this->taskTagModel->dissociateTag($task_id, $tag_id);
        }
    }
}
