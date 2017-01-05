<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Subscriber;

use Jitamin\Bus\Event\TaskEvent;
use Jitamin\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Transition Subscriber.
 */
class TransitionSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners.
     *
     * @static
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TaskModel::EVENT_MOVE_COLUMN => 'execute',
        ];
    }

    /**
     * Handle the event.
     *
     * @param TaskEvent $event
     */
    public function execute(TaskEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);

        $user_id = $this->userSession->getId();

        if (!empty($user_id)) {
            $this->transitionModel->save($user_id, $event->getAll());
        }
    }
}
