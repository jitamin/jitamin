<?php $has_project_creation_access = $this->user->hasAccess('ProjectController', 'create'); ?>
<?php $is_private_project_enabled = $this->app->setting('disable_private_project', 0) == 0; ?>
<div class="sidebar">
    <div class="sidememu">
        <a href="/"><div class="menu-top"></div></a>
        <div class="menu-tab">
            <ul class="sidebar-menu">
                <li <?= $this->app->setActive('DashboardController', 'index') ?>>
                    <?= $this->url->link('<i class="fa fa-dashboard"></i><br />'.t('My'), 'DashboardController', 'index') ?>
                </li>
                <li <?= $this->app->setActive('SearchController', 'index') ?>>
                    <?= $this->url->link('<i class="fa fa-search"></i><br />'.t('Search'), 'SearchController', 'index') ?>
                </li>
                <li <?= $this->app->setActive('DashboardController', 'notifications') ?>>
                    <?php if ($this->user->hasNotifications()): ?>
                        <?= $this->url->link('<i class="fa fa-bell web-notification-icon"></i><br />'.t('Notice'), 'DashboardController', 'notifications', [], false, '', t('You have unread notifications')) ?>
                    <?php else: ?>
                        <?= $this->url->link('<i class="fa fa-bell"></i><br />'.t('Notice'), 'DashboardController', 'notifications', [], false, '', t('You have no unread notifications')) ?>
                    <?php endif ?>
                </li>
                <?php if ($this->user->hasAccess('SettingController', 'index')): ?>
                <hr/>
                <li <?= $this->app->setActive('SettingController', 'index') ?>>
                    <?= $this->url->link('<i class="fa fa-gear"></i><br />'.t('Settings'), 'SettingController', 'index') ?>
                </li>
                <?php endif ?>
            </ul>
        </div>
        <div class="menu-bottom"></div>
    </div>
</div>