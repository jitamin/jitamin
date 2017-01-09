<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Decorator;

use Jitamin\Foundation\Cache\CacheInterface;
use Jitamin\Model\ProjectRoleRestrictionModel;

/**
 * Class ProjectRoleRestrictionCacheDecorator.
 */
class ProjectRoleRestrictionCacheDecorator
{
    protected $cachePrefix = 'project_restriction:';

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var ProjectRoleRestrictionModel
     */
    protected $projectRoleRestrictionModel;

    /**
     * ColumnMoveRestrictionDecorator constructor.
     *
     * @param CacheInterface              $cache
     * @param ProjectRoleRestrictionModel $projectRoleRestrictionModel
     */
    public function __construct(CacheInterface $cache, ProjectRoleRestrictionModel $projectRoleRestrictionModel)
    {
        $this->cache = $cache;
        $this->projectRoleRestrictionModel = $projectRoleRestrictionModel;
    }

    /**
     * Proxy method to get sortable columns.
     *
     * @param int    $project_id
     * @param string $role
     *
     * @return array|mixed
     */
    public function getAllByRole($project_id, $role)
    {
        $key = $this->cachePrefix.$project_id.$role;
        $projectRestrictions = $this->cache->get($key);

        if ($projectRestrictions === null) {
            $projectRestrictions = $this->projectRoleRestrictionModel->getAllByRole($project_id, $role);
            $this->cache->set($key, $projectRestrictions);
        }

        return $projectRestrictions;
    }
}
