<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Decorator;

use Jitamin\Core\Cache\CacheInterface;
use Jitamin\Model\ColumnRestrictionModel;

/**
 * Class ColumnRestrictionCacheDecorator.
 */
class ColumnRestrictionCacheDecorator
{
    protected $cachePrefix = 'column_restriction:';

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var ColumnRestrictionModel
     */
    protected $columnRestrictionModel;

    /**
     * ColumnMoveRestrictionDecorator constructor.
     *
     * @param CacheInterface         $cache
     * @param ColumnRestrictionModel $columnMoveRestrictionModel
     */
    public function __construct(CacheInterface $cache, ColumnRestrictionModel $columnMoveRestrictionModel)
    {
        $this->cache = $cache;
        $this->columnRestrictionModel = $columnMoveRestrictionModel;
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
        $columnRestrictions = $this->cache->get($key);

        if ($columnRestrictions === null) {
            $columnRestrictions = $this->columnRestrictionModel->getAllByRole($project_id, $role);
            $this->cache->set($key, $columnRestrictions);
        }

        return $columnRestrictions;
    }
}
