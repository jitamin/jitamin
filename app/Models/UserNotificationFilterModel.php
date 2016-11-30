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

use Hiject\Core\Base;

/**
 * User Notification Filter.
 */
class UserNotificationFilterModel extends Base
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const PROJECT_TABLE = 'user_has_notifications';

    /**
     * User filters.
     *
     * @var int
     */
    const FILTER_NONE = 1;
    const FILTER_ASSIGNEE = 2;
    const FILTER_CREATOR = 3;
    const FILTER_BOTH = 4;

    /**
     * Get the list of filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            self::FILTER_NONE     => t('All tasks'),
            self::FILTER_ASSIGNEE => t('Only for tasks assigned to me'),
            self::FILTER_CREATOR  => t('Only for tasks created by me'),
            self::FILTER_BOTH     => t('Only for tasks created by me and assigned to me'),
        ];
    }

    /**
     * Get user selected filter.
     *
     * @param int $user_id
     *
     * @return int
     */
    public function getSelectedFilter($user_id)
    {
        return $this->db->table(UserModel::TABLE)->eq('id', $user_id)->findOneColumn('notifications_filter');
    }

    /**
     * Save selected filter for a user.
     *
     * @param int    $user_id
     * @param string $filter
     *
     * @return bool
     */
    public function saveFilter($user_id, $filter)
    {
        return $this->db->table(UserModel::TABLE)->eq('id', $user_id)->update([
            'notifications_filter' => $filter,
        ]);
    }

    /**
     * Get user selected projects.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getSelectedProjects($user_id)
    {
        return $this->db->table(self::PROJECT_TABLE)->eq('user_id', $user_id)->findAllByColumn('project_id');
    }

    /**
     * Save selected projects for a user.
     *
     * @param int   $user_id
     * @param int[] $project_ids
     *
     * @return bool
     */
    public function saveSelectedProjects($user_id, array $project_ids)
    {
        $results = [];
        $this->db->table(self::PROJECT_TABLE)->eq('user_id', $user_id)->remove();

        foreach ($project_ids as $project_id) {
            $results[] = $this->db->table(self::PROJECT_TABLE)->insert([
                'user_id'    => $user_id,
                'project_id' => $project_id,
            ]);
        }

        return !in_array(false, $results, true);
    }

    /**
     * Return true if the user should receive notification.
     *
     * @param array $user
     * @param array $event_data
     *
     * @return bool
     */
    public function shouldReceiveNotification(array $user, array $event_data)
    {
        $filters = [
            'filterNone',
            'filterAssignee',
            'filterCreator',
            'filterBoth',
        ];

        foreach ($filters as $filter) {
            if ($this->$filter($user, $event_data)) {
                return $this->filterProject($user, $event_data);
            }
        }

        return false;
    }

    /**
     * Return true if the user will receive all notifications.
     *
     * @param array $user
     *
     * @return bool
     */
    public function filterNone(array $user)
    {
        return $user['notifications_filter'] == self::FILTER_NONE;
    }

    /**
     * Return true if the user is the assignee and selected the filter "assignee".
     *
     * @param array $user
     * @param array $event_data
     *
     * @return bool
     */
    public function filterAssignee(array $user, array $event_data)
    {
        return $user['notifications_filter'] == self::FILTER_ASSIGNEE && $event_data['task']['owner_id'] == $user['id'];
    }

    /**
     * Return true if the user is the creator and enabled the filter "creator".
     *
     * @param array $user
     * @param array $event_data
     *
     * @return bool
     */
    public function filterCreator(array $user, array $event_data)
    {
        return $user['notifications_filter'] == self::FILTER_CREATOR && $event_data['task']['creator_id'] == $user['id'];
    }

    /**
     * Return true if the user is the assignee or the creator and selected the filter "both".
     *
     * @param array $user
     * @param array $event_data
     *
     * @return bool
     */
    public function filterBoth(array $user, array $event_data)
    {
        return $user['notifications_filter'] == self::FILTER_BOTH &&
               ($event_data['task']['creator_id'] == $user['id'] || $event_data['task']['owner_id'] == $user['id']);
    }

    /**
     * Return true if the user want to receive notification for the selected project.
     *
     * @param array $user
     * @param array $event_data
     *
     * @return bool
     */
    public function filterProject(array $user, array $event_data)
    {
        $projects = $this->getSelectedProjects($user['id']);

        if (!empty($projects)) {
            return in_array($event_data['task']['project_id'], $projects);
        }

        return true;
    }
}
