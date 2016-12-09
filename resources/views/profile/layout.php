<section id="main">
    <div class="page-header">
        <?php if ($this->user->hasAccess('UserController', 'create')): ?>
        <ul>
            <li><i class="fa fa-user fa-fw"></i><?= $this->url->link(t('All users'), 'UserController', 'index') ?></li>
            <li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New local user'), 'UserController', 'create', [], false, 'popover') ?></li>
            <li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New remote user'), 'UserController', 'create', ['remote' => 1], false, 'popover') ?></li>
            <li><i class="fa fa-upload fa-fw"></i><?= $this->url->link(t('Import'), 'UserImportController', 'show', [], false, 'popover') ?></li>
            <li><i class="fa fa-users fa-fw"></i><?= $this->url->link(t('View all groups'), 'GroupController', 'index') ?></li>
        </ul>
        <?php endif ?>
    </div>
    <section class="sidebar-container" id="user-section">
        <?= $this->render('profile/sidebar', ['user' => $user]) ?>
        <div class="sidebar-content">
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
