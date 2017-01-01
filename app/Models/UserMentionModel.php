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

use Jitamin\Bus\Event\GenericEvent;
use Jitamin\Core\Database\Model;

/**
 * User Mention.
 */
class UserMentionModel extends Model
{
    /**
     * Get list of mentioned users.
     *
     * @param string $content
     *
     * @return array
     */
    public function getMentionedUsers($content)
    {
        $users = [];

        if (preg_match_all('/@([^\s]+)/', $content, $matches)) {
            $users = $this->db->table(UserModel::TABLE)
                ->columns('id', 'username', 'name', 'email', 'language')
                ->eq('notifications_enabled', 1)
                ->neq('id', $this->userSession->getId())
                ->in('username', array_unique($matches[1]))
                ->findAll();
        }

        return $users;
    }

    /**
     * Fire events for user mentions.
     *
     * @param string       $content
     * @param string       $eventName
     * @param GenericEvent $event
     */
    public function fireEvents($content, $eventName, GenericEvent $event)
    {
        if (empty($event['project_id'])) {
            $event['project_id'] = $this->taskFinderModel->getProjectId($event['task_id']);
        }

        $users = $this->getMentionedUsers($content);

        foreach ($users as $user) {
            if ($this->projectPermissionModel->isMember($event['project_id'], $user['id'])) {
                $event['mention'] = $user;
                $this->dispatcher->dispatch($eventName, $event);
            }
        }
    }
}
