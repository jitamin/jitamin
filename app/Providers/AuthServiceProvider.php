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

use Jitamin\Auth\ApiTokenAuth;
use Jitamin\Auth\DatabaseAuth;
use Jitamin\Auth\LdapAuth;
use Jitamin\Auth\RememberMeAuth;
use Jitamin\Auth\ReverseProxyAuth;
use Jitamin\Auth\TotpAuth;
use Jitamin\Foundation\Security\AccessMap;
use Jitamin\Foundation\Security\AuthenticationManager;
use Jitamin\Foundation\Security\Authorization;
use Jitamin\Foundation\Security\Role;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class of Authentication Service Provider.
 */
class AuthServiceProvider implements ServiceProviderInterface
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
        $container['authenticationManager'] = new AuthenticationManager($container);
        $container['authenticationManager']->register(new TotpAuth($container));
        $container['authenticationManager']->register(new RememberMeAuth($container));
        $container['authenticationManager']->register(new DatabaseAuth($container));

        if (REVERSE_PROXY_AUTH) {
            $container['authenticationManager']->register(new ReverseProxyAuth($container));
        }

        if (LDAP_AUTH) {
            $container['authenticationManager']->register(new LdapAuth($container));
        }

        $container['authenticationManager']->register(new ApiTokenAuth($container));

        $container['projectAccessMap'] = $this->getProjectAccessMap();
        $container['applicationAccessMap'] = $this->getApplicationAccessMap();
        $container['apiAccessMap'] = $this->getApiAccessMap();
        $container['apiProjectAccessMap'] = $this->getApiProjectAccessMap();

        $container['projectAuthorization'] = new Authorization($container['projectAccessMap']);
        $container['applicationAuthorization'] = new Authorization($container['applicationAccessMap']);
        $container['apiAuthorization'] = new Authorization($container['apiAccessMap']);
        $container['apiProjectAuthorization'] = new Authorization($container['apiProjectAccessMap']);

        return $container;
    }

    /**
     * Get ACL for projects.
     *
     * @return AccessMap
     */
    public function getProjectAccessMap()
    {
        $acl = new AccessMap();
        $acl->setDefaultRole(Role::PROJECT_VIEWER);
        $acl->setRoleHierarchy(Role::PROJECT_MANAGER, [Role::PROJECT_MEMBER, Role::PROJECT_VIEWER]);
        $acl->setRoleHierarchy(Role::PROJECT_MEMBER, [Role::PROJECT_VIEWER]);

        $acl->add('Project/ActionController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectActionDuplicationController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/AnalyticController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/Board/BoardAjaxController', 'store', Role::PROJECT_MEMBER);
        $acl->add('Project/Board/BoardPopoverController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskPopoverController', '*', Role::PROJECT_MEMBER);
        $acl->add('CalendarController', 'store', Role::PROJECT_MEMBER);
        $acl->add('Project/CategoryController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/Column/ColumnController', '*', Role::PROJECT_MANAGER);
        $acl->add('Task/CommentController', '*', Role::PROJECT_MEMBER);
        $acl->add('Project/CustomFilterController', '*', Role::PROJECT_MEMBER);
        $acl->add('Project/ExportController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ImportController', '*', Role::PROJECT_MANAGER);
        $acl->add('Task/TaskFileController', ['screenshot', 'create', 'store', 'remove', 'confirm'], Role::PROJECT_MEMBER);
        $acl->add('Manage/ProjectSettingsController', '*', Role::PROJECT_MANAGER);
        $acl->add('Manage/ProjectPermissionController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectFileController', '*', Role::PROJECT_MEMBER);
        $acl->add('Manage/ProjectController', '*', Role::PROJECT_MANAGER);
        $acl->add('Manage/ProjectUserOverviewController', '*', Role::PROJECT_MANAGER);
        $acl->add('Manage/ProjectStatusController', '*', Role::PROJECT_MANAGER);
        $acl->add('Manage/ProjectTagController', '*', Role::PROJECT_MANAGER);
        $acl->add('Task/Subtask/SubtaskController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/Subtask/SubtaskRestrictionController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/Subtask/SubtaskStatusController', '*', Role::PROJECT_MEMBER);
        $acl->add('Project/SwimlaneController', '*', Role::PROJECT_MANAGER);
        $acl->add('Task/TaskSuppressionController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskController', ['show', 'analytics', 'timetracking', 'transitions'], Role::PROJECT_VIEWER);
        $acl->add('Task/TaskBulkController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskSimpleController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskDuplicationController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskRecurrenceController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskInternalLinkController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskExternalLinkController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskStatusController', '*', Role::PROJECT_MEMBER);
        $acl->add('Profile/UserAjaxController', ['mention'], Role::PROJECT_MEMBER);

        return $acl;
    }

    /**
     * Get ACL for the application.
     *
     * @return AccessMap
     */
    public function getApplicationAccessMap()
    {
        $acl = new AccessMap();
        $acl->setDefaultRole(Role::APP_USER);
        $acl->setRoleHierarchy(Role::APP_ADMIN, [Role::APP_MANAGER, Role::APP_USER, Role::APP_PUBLIC]);
        $acl->setRoleHierarchy(Role::APP_MANAGER, [Role::APP_USER, Role::APP_PUBLIC]);
        $acl->setRoleHierarchy(Role::APP_USER, [Role::APP_PUBLIC]);

        $acl->add('Auth/AuthController', ['login', 'check'], Role::APP_PUBLIC);
        $acl->add('CaptchaController', '*', Role::APP_PUBLIC);
        $acl->add('Auth/PasswordResetController', '*', Role::APP_PUBLIC);
        $acl->add('Task/TaskController', 'readonly', Role::APP_PUBLIC);
        $acl->add('Project/Board/BoardController', 'readonly', Role::APP_PUBLIC);
        $acl->add('ICalendarController', '*', Role::APP_PUBLIC);
        $acl->add('FeedController', '*', Role::APP_PUBLIC);

        $acl->add('Profile/AvatarController', ['show', 'image'], Role::APP_PUBLIC);
        $acl->add('Project/ProjectController', ['create', 'gantt', 'updateDate'], Role::APP_MANAGER);
        $acl->add('Project/ProjectUserOverviewController', '*', Role::APP_MANAGER);
        $acl->add('Profile/TwoFactorController', 'disable', Role::APP_ADMIN);

        $acl->add('Admin/AdminController', '*', Role::APP_ADMIN);
        $acl->add('Admin/SettingController', '*', Role::APP_ADMIN);
        $acl->add('Admin/TagController', '*', Role::APP_ADMIN);
        $acl->add('Admin/PluginController', '*', Role::APP_ADMIN);
        $acl->add('Admin/GroupController', '*', Role::APP_ADMIN);
        $acl->add('Admin/LinkController', '*', Role::APP_ADMIN);
        $acl->add('Admin/UserImportController', '*', Role::APP_ADMIN);
        $acl->add('Admin/UserController', '*', Role::APP_ADMIN);
        $acl->add('Admin/UserStatusController', '*', Role::APP_ADMIN);

        return $acl;
    }

    /**
     * Get ACL for the API.
     *
     * @return AccessMap
     */
    public function getApiAccessMap()
    {
        $acl = new AccessMap();
        $acl->setDefaultRole(Role::APP_USER);
        $acl->setRoleHierarchy(Role::APP_ADMIN, [Role::APP_MANAGER, Role::APP_USER, Role::APP_PUBLIC]);
        $acl->setRoleHierarchy(Role::APP_MANAGER, [Role::APP_USER, Role::APP_PUBLIC]);

        $acl->add('Api/UserController', '*', Role::APP_ADMIN);
        $acl->add('Api/GroupMemberController', '*', Role::APP_ADMIN);
        $acl->add('Api/GroupController', '*', Role::APP_ADMIN);
        $acl->add('Api/LinkController', '*', Role::APP_ADMIN);
        $acl->add('Api/TaskController', ['getOverdueTasks'], Role::APP_ADMIN);
        $acl->add('Api/ProjectController', ['getAllProjects'], Role::APP_ADMIN);
        $acl->add('Api/ProjectController', ['createProject'], Role::APP_MANAGER);

        return $acl;
    }

    /**
     * Get ACL for the API.
     *
     * @return AccessMap
     */
    public function getApiProjectAccessMap()
    {
        $acl = new AccessMap();
        $acl->setDefaultRole(Role::PROJECT_VIEWER);
        $acl->setRoleHierarchy(Role::PROJECT_MANAGER, [Role::PROJECT_MEMBER, Role::PROJECT_VIEWER]);
        $acl->setRoleHierarchy(Role::PROJECT_MEMBER, [Role::PROJECT_VIEWER]);

        $acl->add('Api/ActionController', ['removeAction', 'getActions', 'createAction'], Role::PROJECT_MANAGER);
        $acl->add('Api/CategoryController', '*', Role::PROJECT_MANAGER);
        $acl->add('Api/ColumnController', '*', Role::PROJECT_MANAGER);
        $acl->add('Api/CommentController', ['removeComment', 'createComment', 'updateComment'], Role::PROJECT_MEMBER);
        $acl->add('Api/ProjectPermissionController', '*', Role::PROJECT_MANAGER);
        $acl->add('Api/ProjectController', ['updateProject', 'removeProject', 'enableProject', 'disableProject', 'enableProjectPublicAccess', 'disableProjectPublicAccess'], Role::PROJECT_MANAGER);
        $acl->add('Api/SubtaskController', '*', Role::PROJECT_MEMBER);
        $acl->add('Api/SubtaskTimeTrackingController', '*', Role::PROJECT_MEMBER);
        $acl->add('Api/SwimlaneController', '*', Role::PROJECT_MANAGER);
        $acl->add('Api/ProjectFileController', '*', Role::PROJECT_MEMBER);
        $acl->add('Api/TaskFileController', '*', Role::PROJECT_MEMBER);
        $acl->add('Api/TaskLinkController', '*', Role::PROJECT_MEMBER);
        $acl->add('Api/askExternalLinkController', ['createExternalTaskLink', 'updateExternalTaskLink', 'removeExternalTaskLink'], Role::PROJECT_MEMBER);
        $acl->add('Api/TaskController', '*', Role::PROJECT_MEMBER);

        return $acl;
    }
}
