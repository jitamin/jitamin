<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\EventBuilder;

use Hiject\Bus\Event\GenericEvent;
use Hiject\Bus\Event\SubtaskEvent;
use Hiject\Model\SubtaskModel;

/**
 * Class SubtaskEventBuilder.
 */
class SubtaskEventBuilder extends BaseEventBuilder
{
    /**
     * SubtaskId.
     *
     * @var int
     */
    protected $subtaskId = 0;

    /**
     * Changed values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Set SubtaskId.
     *
     * @param int $subtaskId
     *
     * @return $this
     */
    public function withSubtaskId($subtaskId)
    {
        $this->subtaskId = $subtaskId;

        return $this;
    }

    /**
     * Set values.
     *
     * @param array $values
     *
     * @return $this
     */
    public function withValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Build event data.
     *
     * @return GenericEvent|null
     */
    public function buildEvent()
    {
        $eventData = [];
        $eventData['subtask'] = $this->subtaskModel->getById($this->subtaskId, true);

        if (empty($eventData['subtask'])) {
            $this->logger->debug(__METHOD__.': Subtask not found');

            return;
        }

        if (!empty($this->values)) {
            $eventData['changes'] = array_diff_assoc($this->values, $eventData['subtask']);
        }

        $eventData['task'] = $this->taskFinderModel->getDetails($eventData['subtask']['task_id']);

        return new SubtaskEvent($eventData);
    }

    /**
     * Get event title with author.
     *
     * @param string $author
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function buildTitleWithAuthor($author, $eventName, array $eventData)
    {
        switch ($eventName) {
            case SubtaskModel::EVENT_UPDATE:
                return e('%s updated a subtask for the task #%d', $author, $eventData['task']['id']);
            case SubtaskModel::EVENT_CREATE:
                return e('%s created a subtask for the task #%d', $author, $eventData['task']['id']);
            case SubtaskModel::EVENT_DELETE:
                return e('%s removed a subtask for the task #%d', $author, $eventData['task']['id']);
            default:
                return '';
        }
    }

    /**
     * Get event title without author.
     *
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function buildTitleWithoutAuthor($eventName, array $eventData)
    {
        switch ($eventName) {
            case SubtaskModel::EVENT_CREATE:
                return e('New subtask on task #%d', $eventData['subtask']['task_id']);
            case SubtaskModel::EVENT_UPDATE:
                return e('Subtask updated on task #%d', $eventData['subtask']['task_id']);
            case SubtaskModel::EVENT_DELETE:
                return e('Subtask removed on task #%d', $eventData['subtask']['task_id']);
            default:
                return '';
        }
    }
}
