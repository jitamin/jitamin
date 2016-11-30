<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Decorator;

use Hiject\Core\Cache\CacheInterface;
use Hiject\Model\ColumnMoveRestrictionModel;

/**
 * Class ColumnMoveRestrictionCacheDecorator.
 */
class ColumnMoveRestrictionCacheDecorator
{
    protected $cachePrefix = 'column_move_restriction:';

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var ColumnMoveRestrictionModel
     */
    protected $columnMoveRestrictionModel;

    /**
     * ColumnMoveRestrictionDecorator constructor.
     *
     * @param CacheInterface             $cache
     * @param ColumnMoveRestrictionModel $columnMoveRestrictionModel
     */
    public function __construct(CacheInterface $cache, ColumnMoveRestrictionModel $columnMoveRestrictionModel)
    {
        $this->cache = $cache;
        $this->columnMoveRestrictionModel = $columnMoveRestrictionModel;
    }

    /**
     * Proxy method to get sortable columns.
     *
     * @param int    $project_id
     * @param string $role
     *
     * @return array|mixed
     */
    public function getSortableColumns($project_id, $role)
    {
        $key = $this->cachePrefix.$project_id.$role;
        $columnIds = $this->cache->get($key);

        if ($columnIds === null) {
            $columnIds = $this->columnMoveRestrictionModel->getSortableColumns($project_id, $role);
            $this->cache->set($key, $columnIds);
        }

        return $columnIds;
    }
}
