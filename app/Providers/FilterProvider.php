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

use Jitamin\Foundation\Filter\LexerBuilder;
use Jitamin\Foundation\Filter\QueryBuilder;
use Jitamin\Filter\ProjectActivityCreationDateFilter;
use Jitamin\Filter\ProjectActivityCreatorFilter;
use Jitamin\Filter\ProjectActivityProjectNameFilter;
use Jitamin\Filter\ProjectActivityTaskStatusFilter;
use Jitamin\Filter\ProjectActivityTaskTitleFilter;
use Jitamin\Filter\TaskAssigneeFilter;
use Jitamin\Filter\TaskCategoryFilter;
use Jitamin\Filter\TaskColorFilter;
use Jitamin\Filter\TaskColumnFilter;
use Jitamin\Filter\TaskCommentFilter;
use Jitamin\Filter\TaskCreationDateFilter;
use Jitamin\Filter\TaskCreatorFilter;
use Jitamin\Filter\TaskDescriptionFilter;
use Jitamin\Filter\TaskDueDateFilter;
use Jitamin\Filter\TaskIdFilter;
use Jitamin\Filter\TaskLinkFilter;
use Jitamin\Filter\TaskModificationDateFilter;
use Jitamin\Filter\TaskMovedDateFilter;
use Jitamin\Filter\TaskPriorityFilter;
use Jitamin\Filter\TaskProjectFilter;
use Jitamin\Filter\TaskReferenceFilter;
use Jitamin\Filter\TaskStartDateFilter;
use Jitamin\Filter\TaskStatusFilter;
use Jitamin\Filter\TaskSubtaskAssigneeFilter;
use Jitamin\Filter\TaskSwimlaneFilter;
use Jitamin\Filter\TaskTagFilter;
use Jitamin\Filter\TaskTitleFilter;
use Jitamin\Model\ProjectGroupRoleModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\ProjectUserRoleModel;
use Jitamin\Model\UserModel;
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

    /**
     * Create user filter.
     *
     * @param Container $container
     *
     * @return Container
     */
    public function createUserFilter(Container $container)
    {
        $container['userQuery'] = $container->factory(function ($c) {
            $builder = new QueryBuilder();
            $builder->withQuery($c['db']->table(UserModel::TABLE));

            return $builder;
        });

        return $container;
    }

    /**
     * Create project filter.
     *
     * @param Container $container
     *
     * @return Container
     */
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

    /**
     * Create task filter.
     *
     * @param Container $container
     *
     * @return Container
     */
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
