<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Subscriber;

use Hiject\Bus\Event\TaskEvent;
use Hiject\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Transition Subscriber
 */
class TransitionSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners
     *
     * @static
     * @access public
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TaskModel::EVENT_MOVE_COLUMN => 'execute',
        ];
    }

    /**
     * Handle the event
     *
     * @access public
     * @param TaskEvent $event
     */
    public function execute(TaskEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);

        $user_id = $this->userSession->getId();

        if (! empty($user_id)) {
            $this->transitionModel->save($user_id, $event->getAll());
        }
    }
}
