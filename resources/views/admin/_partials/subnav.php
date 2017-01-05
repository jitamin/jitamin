<div class="page-header">
    <ul class="nav nav-tabs">
        <li <?= $this->app->setActive('Admin/AdminController', 'index') ?>>
            <?= $this->url->link(t('Overview'), 'Admin/AdminController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController') ?>>
            <?= $this->url->link(t('Application settings'), 'Admin/SettingController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/UserController') ?>>
            <?= $this->url->link(t('Users management'), 'Admin/UserController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/GroupController') ?>>
            <?= $this->url->link(t('Groups management'), 'Admin/GroupController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/TagController') ?>>
            <?= $this->url->link(t('Tags management'), 'Admin/TagController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/LinkController') ?>>
            <?= $this->url->link(t('Link settings'), 'Admin/LinkController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/PluginController') ?>>
            <?= $this->url->link(t('Plugins management'), 'Admin/PluginController', 'show') ?>
        </li>
        <?= $this->hook->render('template:config:subside') ?>
    </ul>
</div>