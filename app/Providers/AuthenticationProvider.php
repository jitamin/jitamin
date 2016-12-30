<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
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
use Jitamin\Core\Security\AccessMap;
use Jitamin\Core\Security\AuthenticationManager;
use Jitamin\Core\Security\Authorization;
use Jitamin\Core\Security\Role;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Authentication Provider.
 */
class AuthenticationProvider implements ServiceProviderInterface
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

        $acl->add('ActionController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectActionDuplicationController', '*', Role::PROJECT_MANAGER);
        $acl->add('AnalyticController', '*', Role::PROJECT_MANAGER);
        $acl->add('BoardAjaxController', 'store', Role::PROJECT_MEMBER);
        $acl->add('BoardPopoverController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskPopoverController', '*', Role::PROJECT_MEMBER);
        $acl->add('CalendarController', 'store', Role::PROJECT_MEMBER);
        $acl->add('CategoryController', '*', Role::PROJECT_MANAGER);
        $acl->add('ColumnController', '*', Role::PROJECT_MANAGER);
        $acl->add('CommentController', '*', Role::PROJECT_MEMBER);
        $acl->add('CustomFilterController', '*', Role::PROJECT_MEMBER);
        $acl->add('ExportController', '*', Role::PROJECT_MANAGER);
        $acl->add('Task/TaskFileController', ['screenshot', 'create', 'store', 'remove', 'confirm'], Role::PROJECT_MEMBER);
        $acl->add('Task/TaskGanttController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectSettingsController', ['share', 'updateSharing', 'integrations', 'updateIntegrations', 'notifications', 'updateNotifications', 'duplicate', 'doDuplication'], Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectPermissionController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectController', ['edit', 'edit_description', 'update'], Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectFileController', '*', Role::PROJECT_MEMBER);
        $acl->add('Project/ProjectUserOverviewController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectStatusController', '*', Role::PROJECT_MANAGER);
        $acl->add('Project/ProjectTagController', '*', Role::PROJECT_MANAGER);
        $acl->add('Task/SubtaskController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/SubtaskRestrictionController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/SubtaskStatusController', '*', Role::PROJECT_MEMBER);
        $acl->add('SwimlaneController', '*', Role::PROJECT_MANAGER);
        $acl->add('Task/TaskSuppressionController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskController', ['show', 'analytics', 'timetracking', 'transitions'], Role::PROJECT_VIEWER);
        $acl->add('Task/TaskBulkController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskDuplicationController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskRecurrenceController', '*', Role::PROJECT_MEMBER);
        $acl->add('Task/TaskImportController', '*', Role::PROJECT_MANAGER);
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
        $acl->add('BoardController', 'readonly', Role::APP_PUBLIC);
        $acl->add('ICalendarController', '*', Role::APP_PUBLIC);
        $acl->add('FeedController', '*', Role::APP_PUBLIC);
        $acl->add('AvatarFileController', ['show', 'image'], Role::APP_PUBLIC);

        $acl->add('Admin/SettingController', '*', Role::APP_ADMIN);
        $acl->add('Admin/TagController', '*', Role::APP_ADMIN);
        $acl->add('Admin/PluginController', '*', Role::APP_ADMIN);
        $acl->add('Admin/GroupController', '*', Role::APP_ADMIN);
        $acl->add('Admin/LinkController', '*', Role::APP_ADMIN);
        $acl->add('Project/ProjectController', ['create', 'gantt', 'updateDate'], Role::APP_MANAGER);
        $acl->add('Project/ProjectUserOverviewController', '*', Role::APP_MANAGER);
        $acl->add('Profile/TwoFactorController', 'disable', Role::APP_ADMIN);
        $acl->add('Admin/UserImportController', '*', Role::APP_ADMIN);
        $acl->add('Admin/UserController', '*', Role::APP_ADMIN);
        $acl->add('Profile/UserStatusController', '*', Role::APP_ADMIN);

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

        $acl->add('UserProcedure', '*', Role::APP_ADMIN);
        $acl->add('GroupMemberProcedure', '*', Role::APP_ADMIN);
        $acl->add('GroupProcedure', '*', Role::APP_ADMIN);
        $acl->add('LinkProcedure', '*', Role::APP_ADMIN);
        $acl->add('TaskProcedure', ['getOverdueTasks'], Role::APP_ADMIN);
        $acl->add('ProjectProcedure', ['getAllProjects'], Role::APP_ADMIN);
        $acl->add('ProjectProcedure', ['createProject'], Role::APP_MANAGER);

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

        $acl->add('ActionProcedure', ['removeAction', 'getActions', 'createAction'], Role::PROJECT_MANAGER);
        $acl->add('CategoryProcedure', '*', Role::PROJECT_MANAGER);
        $acl->add('ColumnProcedure', '*', Role::PROJECT_MANAGER);
        $acl->add('CommentProcedure', ['removeComment', 'createComment', 'updateComment'], Role::PROJECT_MEMBER);
        $acl->add('ProjectPermissionProcedure', '*', Role::PROJECT_MANAGER);
        $acl->add('ProjectProcedure', ['updateProject', 'removeProject', 'enableProject', 'disableProject', 'enableProjectPublicAccess', 'disableProjectPublicAccess'], Role::PROJECT_MANAGER);
        $acl->add('SubtaskProcedure', '*', Role::PROJECT_MEMBER);
        $acl->add('SubtaskTimeTrackingProcedure', '*', Role::PROJECT_MEMBER);
        $acl->add('SwimlaneProcedure', '*', Role::PROJECT_MANAGER);
        $acl->add('ProjectFileProcedure', '*', Role::PROJECT_MEMBER);
        $acl->add('TaskFileProcedure', '*', Role::PROJECT_MEMBER);
        $acl->add('TaskLinkProcedure', '*', Role::PROJECT_MEMBER);
        $acl->add('TaskExternalLinkProcedure', ['createExternalTaskLink', 'updateExternalTaskLink', 'removeExternalTaskLink'], Role::PROJECT_MEMBER);
        $acl->add('TaskProcedure', '*', Role::PROJECT_MEMBER);

        return $acl;
    }
}
