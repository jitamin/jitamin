<div class="sidebar sidebar-icons">
    <ul>
        <li <?= $this->app->checkMenuSelection('SettingController', 'index') ?>>
            <i class="fa fa-cog"></i><?= $this->url->link(t('Application settings'), 'SettingController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'email') ?>>
            <i class="fa fa-envelope-o"></i><?= $this->url->link(t('Email settings'), 'SettingController', 'email') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'project') ?>>
            <i class="fa fa-cube"></i><?= $this->url->link(t('Project settings'), 'SettingController', 'project') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'board') ?>>
            <i class="fa fa-columns"></i><?= $this->url->link(t('Board settings'), 'SettingController', 'board') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'calendar') ?>>
            <i class="fa fa-calendar"></i><?= $this->url->link(t('Calendar settings'), 'SettingController', 'calendar') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('TagController', 'index') ?>>
            <i class="fa fa-tag"></i><?= $this->url->link(t('Tags management'), 'TagController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('LinkController') ?>>
            <i class="fa fa-link"></i><?= $this->url->link(t('Link settings'), 'LinkController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'integrations') ?>>
            <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'SettingController', 'integrations') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'webhook') ?>>
            <i class="fa fa-send-o"></i><?= $this->url->link(t('Webhooks'), 'SettingController', 'webhook') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'api') ?>>
            <i class="fa fa-rocket"></i><?= $this->url->link(t('API'), 'SettingController', 'api') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'help') ?>>
            <i class="fa fa-question-circle"></i><?= $this->url->link(t('Help'), 'SettingController', 'help') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingController', 'about') ?>>
            <i class="fa fa-info-circle"></i><?= $this->url->link(t('About'), 'SettingController', 'about') ?>
        </li>
        <?= $this->hook->render('template:config:sidebar') ?>
    </ul>
</div>