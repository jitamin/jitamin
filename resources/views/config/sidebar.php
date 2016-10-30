<div class="sidebar sidebar-icons">
    <ul>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'index') ?>>
            <i class="fa fa-cog"></i><?= $this->url->link(t('Application settings'), 'ConfigController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'email') ?>>
            <i class="fa fa-envelope-o"></i><?= $this->url->link(t('Email settings'), 'ConfigController', 'email') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'project') ?>>
            <i class="fa fa-cube"></i><?= $this->url->link(t('Project settings'), 'ConfigController', 'project') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'board') ?>>
            <i class="fa fa-columns"></i><?= $this->url->link(t('Board settings'), 'ConfigController', 'board') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'calendar') ?>>
            <i class="fa fa-calendar"></i><?= $this->url->link(t('Calendar settings'), 'ConfigController', 'calendar') ?>
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
        <li <?= $this->app->checkMenuSelection('ConfigController', 'integrations') ?>>
            <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'ConfigController', 'integrations') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'webhook') ?>>
            <i class="fa fa-send-o"></i><?= $this->url->link(t('Webhooks'), 'ConfigController', 'webhook') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'api') ?>>
            <i class="fa fa-rocket"></i><?= $this->url->link(t('API'), 'ConfigController', 'api') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'help') ?>>
            <i class="fa fa-question-circle"></i><?= $this->url->link(t('Help'), 'ConfigController', 'help') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ConfigController', 'about') ?>>
            <i class="fa fa-info-circle"></i><?= $this->url->link(t('About'), 'ConfigController', 'about') ?>
        </li>
        <?= $this->hook->render('template:config:sidebar') ?>
    </ul>
</div>
