<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Providers;

use Hiject\Action\CommentCreation;
use Hiject\Action\CommentCreationMoveTaskColumn;
use Hiject\Action\TaskAssignCategoryColor;
use Hiject\Action\TaskAssignCategoryLabel;
use Hiject\Action\TaskAssignCategoryLink;
use Hiject\Action\TaskAssignColorCategory;
use Hiject\Action\TaskAssignColorColumn;
use Hiject\Action\TaskAssignColorLink;
use Hiject\Action\TaskAssignColorPriority;
use Hiject\Action\TaskAssignColorSwimlane;
use Hiject\Action\TaskAssignColorUser;
use Hiject\Action\TaskAssignCurrentUser;
use Hiject\Action\TaskAssignCurrentUserColumn;
use Hiject\Action\TaskAssignDueDateOnCreation;
use Hiject\Action\TaskAssignPrioritySwimlane;
use Hiject\Action\TaskAssignSpecificUser;
use Hiject\Action\TaskAssignUser;
use Hiject\Action\TaskClose;
use Hiject\Action\TaskCloseColumn;
use Hiject\Action\TaskCloseNoActivity;
use Hiject\Action\TaskCloseNoActivityColumn;
use Hiject\Action\TaskCloseNotMovedColumn;
use Hiject\Action\TaskCreation;
use Hiject\Action\TaskDuplicateAnotherProject;
use Hiject\Action\TaskEmail;
use Hiject\Action\TaskEmailNoActivity;
use Hiject\Action\TaskMoveAnotherProject;
use Hiject\Action\TaskMoveColumnAssigned;
use Hiject\Action\TaskMoveColumnCategoryChange;
use Hiject\Action\TaskMoveColumnClosed;
use Hiject\Action\TaskMoveColumnNotMovedPeriod;
use Hiject\Action\TaskMoveColumnUnAssigned;
use Hiject\Action\TaskOpen;
use Hiject\Action\TaskUpdateStartDate;
use Hiject\Core\Action\ActionManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Action Provider.
 */
class ActionProvider implements ServiceProviderInterface
{
    /**
     * Register providers.
     *
     * @param \Pimple\Container $container
     *
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['actionManager'] = new ActionManager($container);
        $container['actionManager']->register(new CommentCreation($container));
        $container['actionManager']->register(new CommentCreationMoveTaskColumn($container));
        $container['actionManager']->register(new TaskAssignCategoryColor($container));
        $container['actionManager']->register(new TaskAssignCategoryLabel($container));
        $container['actionManager']->register(new TaskAssignCategoryLink($container));
        $container['actionManager']->register(new TaskAssignColorCategory($container));
        $container['actionManager']->register(new TaskAssignColorColumn($container));
        $container['actionManager']->register(new TaskAssignColorLink($container));
        $container['actionManager']->register(new TaskAssignColorUser($container));
        $container['actionManager']->register(new TaskAssignColorPriority($container));
        $container['actionManager']->register(new TaskAssignCurrentUser($container));
        $container['actionManager']->register(new TaskAssignCurrentUserColumn($container));
        $container['actionManager']->register(new TaskAssignSpecificUser($container));
        $container['actionManager']->register(new TaskAssignUser($container));
        $container['actionManager']->register(new TaskClose($container));
        $container['actionManager']->register(new TaskCloseColumn($container));
        $container['actionManager']->register(new TaskCloseNoActivity($container));
        $container['actionManager']->register(new TaskCloseNoActivityColumn($container));
        $container['actionManager']->register(new TaskCloseNotMovedColumn($container));
        $container['actionManager']->register(new TaskCreation($container));
        $container['actionManager']->register(new TaskDuplicateAnotherProject($container));
        $container['actionManager']->register(new TaskEmail($container));
        $container['actionManager']->register(new TaskEmailNoActivity($container));
        $container['actionManager']->register(new TaskMoveAnotherProject($container));
        $container['actionManager']->register(new TaskMoveColumnAssigned($container));
        $container['actionManager']->register(new TaskMoveColumnCategoryChange($container));
        $container['actionManager']->register(new TaskMoveColumnClosed($container));
        $container['actionManager']->register(new TaskMoveColumnNotMovedPeriod($container));
        $container['actionManager']->register(new TaskMoveColumnUnAssigned($container));
        $container['actionManager']->register(new TaskOpen($container));
        $container['actionManager']->register(new TaskUpdateStartDate($container));
        $container['actionManager']->register(new TaskAssignDueDateOnCreation($container));
        $container['actionManager']->register(new TaskAssignColorSwimlane($container));
        $container['actionManager']->register(new TaskAssignPrioritySwimlane($container));

        return $container;
    }
}
