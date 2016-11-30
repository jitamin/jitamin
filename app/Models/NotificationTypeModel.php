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
use Pimple\Container;

/**
 * Notification Type.
 */
abstract class NotificationTypeModel extends Base
{
    /**
     * Container.
     *
     * @var \Pimple\Container
     */
    private $classes;

    /**
     * Notification type labels.
     *
     * @var array
     */
    private $labels = [];

    /**
     * Hidden notification types.
     *
     * @var array
     */
    private $hiddens = [];

    /**
     * Constructor.
     *
     * @param \Pimple\Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->classes = new Container();
    }

    /**
     * Add a new notification type.
     *
     * @param string $type
     * @param string $label
     * @param string $class
     * @param bool   $hidden
     *
     * @return NotificationTypeModel
     */
    public function setType($type, $label, $class, $hidden = false)
    {
        $container = $this->container;

        if ($hidden) {
            $this->hiddens[] = $type;
        } else {
            $this->labels[$type] = $label;
        }

        $this->classes[$type] = function () use ($class, $container) {
            return new $class($container);
        };

        return $this;
    }

    /**
     * Get mail notification type instance.
     *
     * @param string $type
     *
     * @return \Hiject\Core\Notification\NotificationInterface
     */
    public function getType($type)
    {
        return $this->classes[$type];
    }

    /**
     * Get all notification types with labels.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->labels;
    }

    /**
     * Get all hidden notification types.
     *
     * @return array
     */
    public function getHiddenTypes()
    {
        return $this->hiddens;
    }

    /**
     * Keep only loaded notification types.
     *
     * @param string[] $types
     *
     * @return array
     */
    public function filterTypes(array $types)
    {
        $classes = $this->classes;

        return array_filter($types, function ($type) use ($classes) {
            return isset($classes[$type]);
        });
    }
}
