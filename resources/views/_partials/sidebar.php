<?php 
$has_project_creation_access = $this->user->hasAccess('Project/ProjectController', 'create');
$has_task_creation_access = $this->user->hasAccess('Task/TaskSimpleController', 'create');
$is_private_project_enabled = $this->app->setting('disable_private_project', 0) == 0;
?>
<div class="sidebar">
    <div class="sidememu">
        <a href="/"><div class="menu-top"></div></a>
        <div class="menu-tab">
            <ul class="sidebar-menu">
                <li <?= $this->app->setActive('Dashboard/DashboardController', 'index') ?>>
                    <?= $this->url->link('<i class="fa fa-dashboard"></i><br />'.t('My'), 'Dashboard/DashboardController', 'index') ?>
                </li>
                <li <?= $this->app->setActive('SearchController', 'index') ?>>
                    <?= $this->url->link('<i class="fa fa-search"></i><br />'.t('Search'), 'SearchController', 'index') ?>
                </li>
                <li <?= $this->app->setActive('Dashboard/NotificationController', 'index') ?>>
                    <?php if ($this->user->hasNotifications()): ?>
                        <?= $this->url->link('<i class="fa fa-bell web-notification-icon"></i><br />'.t('Notice'), 'Dashboard/NotificationController', 'index', [], false, '', t('You have unread notifications')) ?>
                    <?php else: ?>
                        <?= $this->url->link('<i class="fa fa-bell"></i><br />'.t('Notice'), 'Dashboard/NotificationController', 'index', [], false, '', t('You have no unread notifications')) ?>
                    <?php endif ?>
                </li>
                <?php if ($has_project_creation_access || (!$has_project_creation_access && $is_private_project_enabled) || $has_task_creation_access): ?>
                <hr/>
                <li class="dropdown">
                    <a href="#" class="dropdown-menu"><i class="fa fa-plus-circle"></i><br /><?= t('Create') ?></a>
                    <ul>
                        <?php if ($has_project_creation_access): ?>
                            <li><i class="fa fa-cube"></i>
                                <?= $this->url->link(t('New project'), 'Project/ProjectController', 'create', [], false, 'popover small') ?>
                            </li>
                        <?php endif ?>
                        <?php if ($is_private_project_enabled): ?>
                            <li>
                                <i class="fa fa-lock"></i>
                                <?= $this->url->link(t('New private project'), 'Project/ProjectController', 'createPrivate', [], false, 'popover small') ?>
                            </li>
                        <?php endif ?>
                        <?php if ($has_task_creation_access): ?>
                        <div class="divider"></div>
                        <li>
                            <i class="fa fa-tasks"></i>
                            <?= $this->url->link(t('New task'), 'Task/TaskSimpleController', 'create', [], false, 'popover small') ?>
                        </li>
                        <?php endif ?>
                        <?= $this->hook->render('template:sidebar:creation-dropdown') ?>
                    </ul>
                </li>
                <?php endif ?>
                 <?php if ($this->user->hasAccess('Manage/ProjectController', 'index')): ?>
                <li <?= $this->app->setActive('Manage/ProjectController', 'index') ?>>
                    <?= $this->url->link('<i class="fa fa-wrench"></i><br />'.t('Manage'), 'Manage/ProjectController', 'index', [], false, '', t('Project management')) ?>
                </li>
                <?php endif ?>
                <?php if ($this->user->hasAccess('Admin/AdminController', 'index')): ?>
                <hr/>
                <li <?= $this->app->setActive('Admin/AdminController', 'index') ?>>
                    <?= $this->url->link('<i class="fa fa-gear"></i><br />'.t('Admin'), 'Admin/AdminController', 'index', [], false, '', t('Admin Control Panel')) ?>
                </li>
                <?php endif ?>
            </ul>
        </div>
        <div class="menu-bottom"></div>
    </div>
</div>