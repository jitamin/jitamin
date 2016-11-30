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

use Hiject\Core\Filter\LexerBuilder;
use Hiject\Core\Filter\QueryBuilder;
use Hiject\Filter\ProjectActivityCreationDateFilter;
use Hiject\Filter\ProjectActivityCreatorFilter;
use Hiject\Filter\ProjectActivityProjectNameFilter;
use Hiject\Filter\ProjectActivityTaskStatusFilter;
use Hiject\Filter\ProjectActivityTaskTitleFilter;
use Hiject\Filter\TaskAssigneeFilter;
use Hiject\Filter\TaskCategoryFilter;
use Hiject\Filter\TaskColorFilter;
use Hiject\Filter\TaskColumnFilter;
use Hiject\Filter\TaskCommentFilter;
use Hiject\Filter\TaskCreationDateFilter;
use Hiject\Filter\TaskCreatorFilter;
use Hiject\Filter\TaskDescriptionFilter;
use Hiject\Filter\TaskDueDateFilter;
use Hiject\Filter\TaskIdFilter;
use Hiject\Filter\TaskLinkFilter;
use Hiject\Filter\TaskModificationDateFilter;
use Hiject\Filter\TaskMovedDateFilter;
use Hiject\Filter\TaskPriorityFilter;
use Hiject\Filter\TaskProjectFilter;
use Hiject\Filter\TaskReferenceFilter;
use Hiject\Filter\TaskStartDateFilter;
use Hiject\Filter\TaskStatusFilter;
use Hiject\Filter\TaskSubtaskAssigneeFilter;
use Hiject\Filter\TaskSwimlaneFilter;
use Hiject\Filter\TaskTagFilter;
use Hiject\Filter\TaskTitleFilter;
use Hiject\Model\ProjectGroupRoleModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\ProjectUserRoleModel;
use Hiject\Model\UserModel;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Filter Provider.
 */
class FilterProvider implements ServiceProviderInterface
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
        $this->createUserFilter($container);
        $this->createProjectFilter($container);
        $this->createTaskFilter($container);

        return $container;
    }

    public function createUserFilter(Container $container)
    {
        $container['userQuery'] = $container->factory(function ($c) {
            $builder = new QueryBuilder();
            $builder->withQuery($c['db']->table(UserModel::TABLE));

            return $builder;
        });

        return $container;
    }

    public function createProjectFilter(Container $container)
    {
        $container['projectGroupRoleQuery'] = $container->factory(function ($c) {
            $builder = new QueryBuilder();
            $builder->withQuery($c['db']->table(ProjectGroupRoleModel::TABLE));

            return $builder;
        });

        $container['projectUserRoleQuery'] = $container->factory(function ($c) {
            $builder = new QueryBuilder();
            $builder->withQuery($c['db']->table(ProjectUserRoleModel::TABLE));

            return $builder;
        });

        $container['projectQuery'] = $container->factory(function ($c) {
            $builder = new QueryBuilder();
            $builder->withQuery($c['db']->table(ProjectModel::TABLE));

            return $builder;
        });

        $container['projectActivityLexer'] = $container->factory(function ($c) {
            $builder = new LexerBuilder();
            $builder
                ->withQuery($c['projectActivityModel']->getQuery())
                ->withFilter(new ProjectActivityTaskTitleFilter(), true)
                ->withFilter(new ProjectActivityTaskStatusFilter())
                ->withFilter(new ProjectActivityProjectNameFilter())
                ->withFilter(ProjectActivityCreationDateFilter::getInstance()
                    ->setDateParser($c['dateParser'])
                )
                ->withFilter(ProjectActivityCreatorFilter::getInstance()
                    ->setCurrentUserId($c['userSession']->getId())
                );

            return $builder;
        });

        $container['projectActivityQuery'] = $container->factory(function ($c) {
            $builder = new QueryBuilder();
            $builder->withQuery($c['projectActivityModel']->getQuery());

            return $builder;
        });

        return $container;
    }

    public function createTaskFilter(Container $container)
    {
        $container['taskQuery'] = $container->factory(function ($c) {
            $builder = new QueryBuilder();
            $builder->withQuery($c['taskFinderModel']->getExtendedQuery());

            return $builder;
        });

        $container['taskLexer'] = $container->factory(function ($c) {
            $builder = new LexerBuilder();

            $builder
                ->withQuery($c['taskFinderModel']->getExtendedQuery())
                ->withFilter(TaskAssigneeFilter::getInstance()
                    ->setCurrentUserId($c['userSession']->getId())
                )
                ->withFilter(new TaskCategoryFilter())
                ->withFilter(TaskColorFilter::getInstance()
                    ->setColorModel($c['colorModel'])
                )
                ->withFilter(new TaskPriorityFilter())
                ->withFilter(new TaskColumnFilter())
                ->withFilter(new TaskCommentFilter())
                ->withFilter(TaskCreationDateFilter::getInstance()
                    ->setDateParser($c['dateParser'])
                )
                ->withFilter(TaskCreatorFilter::getInstance()
                    ->setCurrentUserId($c['userSession']->getId())
                )
                ->withFilter(new TaskDescriptionFilter())
                ->withFilter(TaskDueDateFilter::getInstance()
                    ->setDateParser($c['dateParser'])
                )
                ->withFilter(TaskStartDateFilter::getInstance()
                    ->setDateParser($c['dateParser'])
                )
                ->withFilter(new TaskIdFilter())
                ->withFilter(TaskLinkFilter::getInstance()
                    ->setDatabase($c['db'])
                )
                ->withFilter(TaskModificationDateFilter::getInstance()
                    ->setDateParser($c['dateParser'])
                )
                ->withFilter(TaskMovedDateFilter::getInstance()
                    ->setDateParser($c['dateParser'])
                )
                ->withFilter(new TaskProjectFilter())
                ->withFilter(new TaskReferenceFilter())
                ->withFilter(new TaskStatusFilter())
                ->withFilter(TaskSubtaskAssigneeFilter::getInstance()
                    ->setCurrentUserId($c['userSession']->getId())
                    ->setDatabase($c['db'])
                )
                ->withFilter(new TaskSwimlaneFilter())
                ->withFilter(TaskTagFilter::getInstance()
                    ->setDatabase($c['db'])
                )
                ->withFilter(new TaskTitleFilter(), true);

            return $builder;
        });

        return $container;
    }
}
