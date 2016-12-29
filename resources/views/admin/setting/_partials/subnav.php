<div class="page-header">
    <ul class="nav nav-tabs">
        <li <?= $this->app->setActive('Admin/SettingController', 'index') ?>>
            <?= $this->url->link(t('Application settings'), 'Admin/SettingController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'email') ?>>
            <?= $this->url->link(t('Email settings'), 'Admin/SettingController', 'email') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'project') ?>>
            <?= $this->url->link(t('Project settings'), 'Admin/SettingController', 'project') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'board') ?>>
            <?= $this->url->link(t('Board settings'), 'Admin/SettingController', 'board') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'calendar') ?>>
            <?= $this->url->link(t('Calendar settings'), 'Admin/SettingController', 'calendar') ?>
        </li>
        <li <?= $this->app->setActive('TagController', 'index') ?>>
            <?= $this->url->link(t('Tags management'), 'TagController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('LinkController') ?>>
            <?= $this->url->link(t('Link settings'), 'LinkController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'integrations') ?>>
            <?= $this->url->link(t('Integrations'), 'Admin/SettingController', 'integrations') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'webhook') ?>>
            <?= $this->url->link(t('Webhooks'), 'Admin/SettingController', 'webhook') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'api') ?>>
            <?= $this->url->link(t('API'), 'Admin/SettingController', 'api') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'help') ?>>
            <?= $this->url->link(t('Help'), 'Admin/SettingController', 'help') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'about') ?>>
            <?= $this->url->link(t('About'), 'Admin/SettingController', 'about') ?>
        </li>
        <?= $this->hook->render('template:config:subside') ?>
    </ul>
</div>