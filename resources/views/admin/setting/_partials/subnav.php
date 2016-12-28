<div class="page-header">
    <ul class="nav nav-tabs">
        <li <?= $this->app->setActive('SettingController', 'index') ?>>
            <?= $this->url->link(t('Application settings'), 'SettingController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'email') ?>>
            <?= $this->url->link(t('Email settings'), 'SettingController', 'email') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'project') ?>>
            <?= $this->url->link(t('Project settings'), 'SettingController', 'project') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'board') ?>>
            <?= $this->url->link(t('Board settings'), 'SettingController', 'board') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'calendar') ?>>
            <?= $this->url->link(t('Calendar settings'), 'SettingController', 'calendar') ?>
        </li>
        <li <?= $this->app->setActive('TagController', 'index') ?>>
            <?= $this->url->link(t('Tags management'), 'TagController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('LinkController') ?>>
            <?= $this->url->link(t('Link settings'), 'LinkController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'integrations') ?>>
            <?= $this->url->link(t('Integrations'), 'SettingController', 'integrations') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'webhook') ?>>
            <?= $this->url->link(t('Webhooks'), 'SettingController', 'webhook') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'api') ?>>
            <?= $this->url->link(t('API'), 'SettingController', 'api') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'help') ?>>
            <?= $this->url->link(t('Help'), 'SettingController', 'help') ?>
        </li>
        <li <?= $this->app->setActive('SettingController', 'about') ?>>
            <?= $this->url->link(t('About'), 'SettingController', 'about') ?>
        </li>
        <?= $this->hook->render('template:config:subside') ?>
    </ul>
</div>