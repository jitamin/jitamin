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

use Hiject\Bus\EventBuilder\CommentEventBuilder;
use Hiject\Bus\EventBuilder\EventIteratorBuilder;
use Hiject\Bus\EventBuilder\SubtaskEventBuilder;
use Hiject\Bus\EventBuilder\TaskEventBuilder;
use Hiject\Bus\EventBuilder\TaskFileEventBuilder;
use Hiject\Bus\EventBuilder\TaskLinkEventBuilder;
use Hiject\Core\Database\Model;

/**
 * Notification Model.
 */
class NotificationModel extends Model
{
    /**
     * Get the event title with author.
     *
     * @param string $eventAuthor
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function getTitleWithAuthor($eventAuthor, $eventName, array $eventData)
    {
        foreach ($this->getIteratorBuilder() as $builder) {
            $title = $builder->buildTitleWithAuthor($eventAuthor, $eventName, $eventData);

            if ($title !== '') {
                return $title;
            }
        }

        return e('Notification');
    }

    /**
     * Get the event title without author.
     *
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function getTitleWithoutAuthor($eventName, array $eventData)
    {
        foreach ($this->getIteratorBuilder() as $builder) {
            $title = $builder->buildTitleWithoutAuthor($eventName, $eventData);

            if ($title !== '') {
                return $title;
            }
        }

        return e('Notification');
    }

    /**
     * Get task id from event.
     *
     * @param string $eventName
     * @param array  $eventData
     *
     * @return int
     */
    public function getTaskIdFromEvent($eventName, array $eventData)
    {
        if ($eventName === TaskModel::EVENT_OVERDUE) {
            return $eventData['tasks'][0]['id'];
        }

        return isset($eventData['task']['id']) ? $eventData['task']['id'] : 0;
    }

    /**
     * Get iterator builder.
     *
     * @return EventIteratorBuilder
     */
    protected function getIteratorBuilder()
    {
        $iterator = new EventIteratorBuilder();
        $iterator
            ->withBuilder(TaskEventBuilder::getInstance($this->container))
            ->withBuilder(CommentEventBuilder::getInstance($this->container))
            ->withBuilder(SubtaskEventBuilder::getInstance($this->container))
            ->withBuilder(TaskFileEventBuilder::getInstance($this->container))
            ->withBuilder(TaskLinkEventBuilder::getInstance($this->container));

        return $iterator;
    }
}
