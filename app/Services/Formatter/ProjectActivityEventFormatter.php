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

use Hiject\Core\Filter\FormatterInterface;

/**
 * Project activity event formatter.
 */
class ProjectActivityEventFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Apply formatter.
     *
     * @return array
     */
    public function format()
    {
        $events = $this->query->findAll();

        foreach ($events as &$event) {
            $event += $this->unserializeEvent($event['data']);
            unset($event['data']);

            $event['author'] = $event['author_name'] ?: $event['author_username'];
            $event['event_title'] = $this->notificationModel->getTitleWithAuthor($event['author'], $event['event_name'], $event);
            $event['event_content'] = $this->renderEvent($event);
        }

        return $events;
    }

    /**
     * Decode event data, supports unserialize() and json_decode().
     *
     * @param string $data Serialized data
     *
     * @return array
     */
    protected function unserializeEvent($data)
    {
        if ($data[0] === 'a') {
            return unserialize($data);
        }

        return json_decode($data, true) ?: [];
    }

    /**
     * Get the event html content.
     *
     * @param array $params Event properties
     *
     * @return string
     */
    protected function renderEvent(array $params)
    {
        return $this->template->render(
            'event/'.str_replace('.', '_', $params['event_name']),
            $params
        );
    }
}
