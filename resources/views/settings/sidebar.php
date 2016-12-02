<div class="sidebar sidebar-icons">
    <ul>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'index') ?>>
            <i class="fa fa-cog"></i><?= $this->url->link(t('Application settings'), 'SettingsController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'email') ?>>
            <i class="fa fa-envelope-o"></i><?= $this->url->link(t('Email settings'), 'SettingsController', 'email') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'project') ?>>
            <i class="fa fa-cube"></i><?= $this->url->link(t('Project settings'), 'SettingsController', 'project') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'board') ?>>
            <i class="fa fa-columns"></i><?= $this->url->link(t('Board settings'), 'SettingsController', 'board') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'calendar') ?>>
            <i class="fa fa-calendar"></i><?= $this->url->link(t('Calendar settings'), 'SettingsController', 'calendar') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('TagController', 'index') ?>>
            <i class="fa fa-tag"></i><?= $this->url->link(t('Tags management'), 'TagController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('LinkController') ?>>
            <i class="fa fa-link"></i><?= $this->url->link(t('Link settings'), 'LinkController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('CurrencyController', 'index') ?>>
            <i class="fa fa-money"></i><?= $this->url->link(t('Currency rates'), 'CurrencyController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'integrations') ?>>
            <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'SettingsController', 'integrations') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'webhook') ?>>
            <i class="fa fa-send-o"></i><?= $this->url->link(t('Webhooks'), 'SettingsController', 'webhook') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'api') ?>>
            <i class="fa fa-rocket"></i><?= $this->url->link(t('API'), 'SettingsController', 'api') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'help') ?>>
            <i class="fa fa-question-circle"></i><?= $this->url->link(t('Help'), 'SettingsController', 'help') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('SettingsController', 'about') ?>>
            <i class="fa fa-info-circle"></i><?= $this->url->link(t('About'), 'SettingsController', 'about') ?>
        </li>
        <?= $this->hook->render('template:config:sidebar') ?>
    </ul>
</div>
