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

use Hiject\Core\Http\Client as HttpClient;
use Hiject\Core\Http\OAuth2;
use Hiject\Core\ObjectStorage\FileStorage;
use Hiject\Core\Paginator;
use Hiject\Core\Tool;
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
            'ConfigModel',
            'CurrencyModel',
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
            'ProjectTaskDuplicationModel',
            'ProjectTaskPriorityModel',
            'ProjectUserRoleModel',
            'RememberMeSessionModel',
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
            'TaskCreationModel',
            'TaskDuplicationModel',
            'TaskProjectDuplicationModel',
            'TaskProjectMoveModel',
            'TaskRecurrenceModel',
            'TaskExternalLinkModel',
            'TaskFinderModel',
            'TaskFileModel',
            'TaskLinkModel',
            'TaskModificationModel',
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
            'CurrencyValidator',
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
        'Core\User' => [
            'GroupSync',
            'UserSync',
            'UserSession',
            'UserProfile',
        ],
    ];

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

                $config = require CONFIG_DIR . DIRECTORY_SEPARATOR . 'memcached.php';

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
