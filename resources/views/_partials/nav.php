<?php $has_project_creation_access = $this->user->hasAccess('ProjectController', 'create'); ?>
<?php $is_private_project_enabled = $this->app->setting('disable_private_project', 0) == 0; ?>
<div class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nb-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <h3>
            <span class="sidebar-toggle"><i class="fa fa-navicon"></i></span>
            <?php if (isset($page_title)): ?>
                <?= $this->text->e($page_title) ?>
            <?php elseif (isset($title)): ?>
                <?= $this->text->e($title) ?>
            <?php else: ?>
                Jitamin
            <?php endif ?>
        </h3>
    </div>
    <div class="collapse navbar-collapse" id="nb-collapse">

        <ul class="nav navbar-nav navbar-right">
            <?php if ($this->user->hasAccess('SettingController', 'index')): ?>
            <li class="dropdown">
                <a href="#" class="dropdown-menu"><i class="fa fa-wrench"></i> <i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <i class="fa fa-user"></i>
                        <?= $this->url->link(t('Users management'), 'UserController', 'index') ?>
                    </li>
                    <li>
                        <i class="fa fa-group"></i>
                        <?= $this->url->link(t('Groups management'), 'GroupController', 'index') ?>
                    </li>
                    <li>
                        <i class="fa fa-plug"></i>
                        <?= $this->url->link(t('Plugins management'), 'PluginController', 'show') ?>
                    </li>
                </ul>
            </li>
            <?php endif ?>
            <!--
            <li class="notification">
                <?php if ($this->user->hasNotifications()): ?>
                    <?= $this->url->link('<i class="fa fa-bell web-notification-icon"></i>', 'DashboardController', 'notifications', [], false, '', t('You have unread notifications')) ?>
                <?php else: ?>
                    <?= $this->url->link('<i class="fa fa-bell"></i>', 'DashboardController', 'notifications', [], false, '', t('You have no unread notifications')) ?>
                <?php endif ?>
            </li>
            <?php if ($has_project_creation_access || (!$has_project_creation_access && $is_private_project_enabled)): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-menu"><i class="fa fa-plus"></i> <i class="fa fa-caret-down"></i></a>
                    <ul>
                        <?php if ($has_project_creation_access): ?>
                            <li><i class="fa fa-cube"></i>
                                <?= $this->url->link(t('New project'), 'ProjectController', 'create', [], false, 'popover') ?>
                            </li>
                        <?php endif ?>
                        <?php if ($is_private_project_enabled): ?>
                            <li>
                                <i class="fa fa-lock"></i>
                                <?= $this->url->link(t('New private project'), 'ProjectController', 'createPrivate', [], false, 'popover') ?>
                            </li>
                        <?php endif ?>
                        <?= $this->hook->render('template:header:creation-dropdown') ?>
                    </ul>
                </li>
            <?php endif ?>
            -->

            <li class="dropdown">
                <a href="#" class="dropdown-menu"><?= $this->avatar->currentUserSmall('avatar-inline') ?><?= $this->text->e($this->user->getFullname()) ?> <i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <i class="fa fa-vcard"></i>
                        <?= $this->url->link(t('My profile'), 'ProfileController', 'show', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <li>
                        <i class="fa fa-edit"></i>
                        <?= $this->url->link(t('Edit profile'), 'ProfileController', 'edit', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <li>
                        <i class="fa fa-life-ring"></i>
                        <?= $this->url->link(t('Documentation'), 'DocumentationController', 'show') ?>
                    </li>
                    <?= $this->hook->render('template:header:dropdown') ?>

                    <?php if (!DISABLE_LOGOUT): ?>
                        <li>
                            <i class="fa fa-sign-out"></i>
                            <?= $this->url->link(t('Logout'), 'AuthController', 'logout') ?>
                        </li>
                    <?php endif ?>
                </ul>
            </li>
        </ul>
    </div>
</div>
