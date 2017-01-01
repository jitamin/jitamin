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

use Jitamin\Core\Http\Client as HttpClient;
use Jitamin\Core\Http\OAuth2;
use Jitamin\Core\ObjectStorage\FileStorage;
use Jitamin\Core\Paginator;
use Jitamin\Core\Tool;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ClassProvider.
 */
class ClassProvider implements ServiceProviderInterface
{
    private $classes = [
        'Analytic' => [
            'TaskDistributionAnalytic',
            'UserDistributionAnalytic',
            'EstimatedTimeComparisonAnalytic',
            'AverageLeadCycleTimeAnalytic',
            'AverageTimeSpentColumnAnalytic',
        ],
        'Model' => [
            'ActionModel',
            'ActionParameterModel',
            'AvatarFileModel',
            'BoardModel',
            'CategoryModel',
            'ColorModel',
            'ColumnModel',
            'ColumnRestrictionModel',
            'ColumnMoveRestrictionModel',
            'CommentModel',
            'CustomFilterModel',
            'GroupModel',
            'GroupMemberModel',
            'LanguageModel',
            'LastLoginModel',
            'LinkModel',
            'NotificationModel',
            'PasswordResetModel',
            'ProjectModel',
            'ProjectFileModel',
            'ProjectActivityModel',
            'ProjectDuplicationModel',
            'ProjectDailyColumnStatsModel',
            'ProjectDailyStatsModel',
            'ProjectPermissionModel',
            'ProjectNotificationModel',
            'ProjectMetadataModel',
            'ProjectGroupRoleModel',
            'ProjectRoleModel',
            'ProjectRoleRestrictionModel',
            'ProjectStarModel',
            'ProjectTaskDuplicationModel',
            'ProjectTaskPriorityModel',
            'ProjectUserRoleModel',
            'RememberMeSessionModel',
            'SettingModel',
            'SkinModel',
            'SubtaskModel',
            'SubtaskPositionModel',
            'SubtaskStatusModel',
            'SubtaskTaskConversionModel',
            'SubtaskTimeTrackingModel',
            'SwimlaneModel',
            'TagDuplicationModel',
            'TagModel',
            'TaskModel',
            'TaskAnalyticModel',
            'TaskDuplicationModel',
            'TaskProjectDuplicationModel',
            'TaskProjectMoveModel',
            'TaskRecurrenceModel',
            'TaskExternalLinkModel',
            'TaskFinderModel',
            'TaskFileModel',
            'TaskLinkModel',
            'TaskPositionModel',
            'TaskStatusModel',
            'TaskTagModel',
            'TaskMetadataModel',
            'TimezoneModel',
            'TransitionModel',
            'UserModel',
            'UserLockingModel',
            'UserMentionModel',
            'UserNotificationModel',
            'UserNotificationFilterModel',
            'UserUnreadNotificationModel',
            'UserMetadataModel',
        ],
        'Validator' => [
            'ActionValidator',
            'AuthValidator',
            'CategoryValidator',
            'ColumnMoveRestrictionValidator',
            'ColumnRestrictionValidator',
            'ColumnValidator',
            'CommentValidator',
            'CustomFilterValidator',
            'ExternalLinkValidator',
            'GroupValidator',
            'LinkValidator',
            'PasswordResetValidator',
            'ProjectValidator',
            'ProjectRoleValidator',
            'SubtaskValidator',
            'SwimlaneValidator',
            'TagValidator',
            'TaskLinkValidator',
            'TaskValidator',
            'UserValidator',
        ],
        'Import' => [
            'TaskImport',
            'UserImport',
        ],
        'Export' => [
            'SubtaskExport',
            'TaskExport',
            'TransitionExport',
        ],
        'Pagination' => [
            'TaskPagination',
            'StarPagination',
            'SubtaskPagination',
            'ProjectPagination',
            'UserPagination',
        ],
        'Core' => [
            'DateParser',
            'Lexer',
        ],
        'Core\Event' => [
            'EventManager',
        ],
        'Core\Http' => [
            'Request',
            'Response',
            'RememberMeCookie',
        ],
        'Core\Plugin' => [
            'Hook',
        ],
        'Core\Security' => [
            'Token',
            'Role',
        ],
        'Core\Identity' => [
            'GroupSync',
            'UserSync',
            'UserSession',
            'UserProfile',
        ],
    ];

    /**
     * Registers services on the given container.
     *
     * @param Container $container
     *
     * @return Container
     */
    public function register(Container $container)
    {
        Tool::buildDIC($container, $this->classes);

        $container['paginator'] = $container->factory(function ($c) {
            return new Paginator($c);
        });

        $container['oauth'] = $container->factory(function ($c) {
            return new OAuth2($c);
        });

        $container['httpClient'] = function ($c) {
            return new HttpClient($c);
        };

        if (CACHE_DRIVER === 'memcached') {
            $container['memcached'] = function ($c) {
                $memcached = new \Memcached();

                $config = require JITAMIN_DIR.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'memcached.php';

                foreach ($config['servers'] as $server) {
                    $memcached->addServer(
                        $server['host'], $server['port'], $server['weight']
                    );
                }

                return $memcached;
            };
        }

        $container['objectStorage'] = function () {
            return new FileStorage(FILES_DIR);
        };

        $container['cspRules'] = [
            'default-src' => "'self'",
            'style-src'   => "'self' 'unsafe-inline'",
            'img-src'     => '* data:',
        ];

        return $container;
    }
}
