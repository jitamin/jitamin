<div class="page-header">
    <ul class="nav nav-tabs">
        <li <?= $this->app->setActive('Admin/AdminController', 'index') ?>>
            <?= $this->url->link(t('Admin'), 'Admin/AdminController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'index') ?>>
            <?= $this->url->link(t('Application settings'), 'Admin/SettingController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/UserController', 'index') ?>>
            <?= $this->url->link(t('Users management'), 'Admin/UserController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/GroupController', 'index') ?>>
            <?= $this->url->link(t('Groups management'), 'Admin/GroupController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/TagController', 'index') ?>>
            <?= $this->url->link(t('Tags management'), 'Admin/TagController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/LinkController') ?>>
            <?= $this->url->link(t('Link settings'), 'Admin/LinkController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/PluginController', 'show') ?>>
            <?= $this->url->link(t('Plugins management'), 'Admin/PluginController', 'show') ?>
        </li>
        <?= $this->hook->render('template:config:subside') ?>
    </ul>
</div>