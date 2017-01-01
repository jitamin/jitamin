<div class="subside">
    <ul>
        <li <?= $this->app->setActive('Admin/PluginController', 'show') ?>>
            <?= $this->url->link(t('Installed Plugins'), 'Admin/PluginController', 'show') ?>
        </li>
        <li <?= $this->app->setActive('Admin/PluginController', 'directory') ?>>
            <?= $this->url->link(t('Plugin Directory'), 'Admin/PluginController', 'directory') ?>
        </li>
    </ul>
</div>
