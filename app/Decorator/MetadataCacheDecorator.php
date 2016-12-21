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
use Jitamin\Model\MetadataModel;

/**
 * Class MetadataCacheDecorator.
 */
class MetadataCacheDecorator
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var MetadataModel
     */
    protected $metadataModel;

    /**
     * @var string
     */
    protected $cachePrefix;

    /**
     * @var int
     */
    protected $entityId;

    /**
     * Constructor.
     *
     * @param CacheInterface $cache
     * @param MetadataModel  $metadataModel
     * @param string         $cachePrefix
     * @param int            $entityId
     */
    public function __construct(CacheInterface $cache, MetadataModel $metadataModel, $cachePrefix, $entityId)
    {
        $this->cache = $cache;
        $this->metadataModel = $metadataModel;
        $this->cachePrefix = $cachePrefix;
        $this->entityId = $entityId;
    }

    /**
     * Get metadata value by key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default)
    {
        $metadata = $this->cache->get($this->getCacheKey());

        if ($metadata === null) {
            $metadata = $this->metadataModel->getAll($this->entityId);
            $this->cache->set($this->getCacheKey(), $metadata);
        }

        return isset($metadata[$key]) ? $metadata[$key] : $default;
    }

    /**
     * Set new metadata value.
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->metadataModel->save($this->entityId, [
            $key => $value,
        ]);

        $metadata = $this->metadataModel->getAll($this->entityId);
        $this->cache->set($this->getCacheKey(), $metadata);
    }

    /**
     * Get cache key.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix.$this->entityId;
    }
}
