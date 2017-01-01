<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Action\CommentCreation;
use Jitamin\Action\CommentCreationMoveTaskColumn;
use Jitamin\Action\TaskAssignCategoryColor;
use Jitamin\Action\TaskAssignCategoryLabel;
use Jitamin\Action\TaskAssignCategoryLink;
use Jitamin\Action\TaskAssignColorCategory;
use Jitamin\Action\TaskAssignColorColumn;
use Jitamin\Action\TaskAssignColorLink;
use Jitamin\Action\TaskAssignColorPriority;
use Jitamin\Action\TaskAssignColorSwimlane;
use Jitamin\Action\TaskAssignColorUser;
use Jitamin\Action\TaskAssignCurrentUser;
use Jitamin\Action\TaskAssignCurrentUserColumn;
use Jitamin\Action\TaskAssignDueDateOnCreation;
use Jitamin\Action\TaskAssignPrioritySwimlane;
use Jitamin\Action\TaskAssignSpecificUser;
use Jitamin\Action\TaskAssignUser;
use Jitamin\Action\TaskClose;
use Jitamin\Action\TaskCloseColumn;
use Jitamin\Action\TaskCloseNoActivity;
use Jitamin\Action\TaskCloseNoActivityColumn;
use Jitamin\Action\TaskCloseNotMovedColumn;
use Jitamin\Action\TaskCreation;
use Jitamin\Action\TaskDuplicateAnotherProject;
use Jitamin\Action\TaskEmail;
use Jitamin\Action\TaskEmailNoActivity;
use Jitamin\Action\TaskMoveAnotherProject;
use Jitamin\Action\TaskMoveColumnAssigned;
use Jitamin\Action\TaskMoveColumnCategoryChange;
use Jitamin\Action\TaskMoveColumnClosed;
use Jitamin\Action\TaskMoveColumnNotMovedPeriod;
use Jitamin\Action\TaskMoveColumnUnAssigned;
use Jitamin\Action\TaskOpen;
use Jitamin\Action\TaskUpdateStartDate;
use Jitamin\Core\Action\ActionManager;
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
