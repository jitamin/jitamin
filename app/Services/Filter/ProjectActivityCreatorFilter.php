<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Filter;

use Jitamin\Core\Filter\FilterInterface;
use Jitamin\Model\ProjectActivityModel;

/**
 * Filter activity events by creator.
 */
class ProjectActivityCreatorFilter extends BaseFilter implements FilterInterface
{
    /**
     * Current user id.
     *
     * @var int
     */
    private $currentUserId = 0;

    /**
     * Set current user id.
     *
     * @param int $userId
     *
     * @return TaskAssigneeFilter
     */
    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;

        return $this;
    }

    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['creator'];
    }

    /**
     * Apply filter.
     *
     * @return string
     */
    public function apply()
    {
        if ($this->value === 'me') {
            $this->query->eq(ProjectActivityModel::TABLE.'.creator_id', $this->currentUserId);
        } else {
            $this->query->beginOr();
            $this->query->ilike('uc.username', '%'.$this->value.'%');
            $this->query->ilike('uc.name', '%'.$this->value.'%');
            $this->query->closeOr();
        }
    }
}
