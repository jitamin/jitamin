<div class="subside subside-icons">
    <ul>
        <li <?= $this->app->setActive('Admin/SettingController', 'index') ?>>
            <i class="fa fa-cog"></i><?= $this->url->link(t('Application settings'), 'Admin/SettingController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'email') ?>>
            <i class="fa fa-envelope-o"></i><?= $this->url->link(t('Email settings'), 'Admin/SettingController', 'email') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'project') ?>>
            <i class="fa fa-cube"></i><?= $this->url->link(t('Project settings'), 'Admin/SettingController', 'project') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'board') ?>>
            <i class="fa fa-columns"></i><?= $this->url->link(t('Board settings'), 'Admin/SettingController', 'board') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'calendar') ?>>
            <i class="fa fa-calendar"></i><?= $this->url->link(t('Calendar settings'), 'Admin/SettingController', 'calendar') ?>
        </li>
        <li <?= $this->app->setActive('Admin/TagController', 'index') ?>>
            <i class="fa fa-tag"></i><?= $this->url->link(t('Tags management'), 'Admin/TagController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/LinkController') ?>>
            <i class="fa fa-link"></i><?= $this->url->link(t('Link settings'), 'Admin/LinkController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'integrations') ?>>
            <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'Admin/SettingController', 'integrations') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'webhook') ?>>
            <i class="fa fa-send-o"></i><?= $this->url->link(t('Webhooks'), 'Admin/SettingController', 'webhook') ?>
        </li>
        <li <?= $this->app->setActive('Admin/SettingController', 'api') ?>>
            <i class="fa fa-rocket"></i><?= $this->url->link(t('API'), 'Admin/SettingController', 'api') ?>
        </li>
        <?= $this->hook->render('template:config:subside') ?>
    </ul>
</div>