<div class="sidebar sidebar-icons">
    <ul>
        <li <?= $this->app->checkMenuSelection('ProjectSettingsController', 'show') ?>>
            <i class="fa fa-eye"></i><?= $this->url->link(t('Summary'), 'ProjectSettingsController', 'show', ['project_id' => $project['id']]) ?>
        </li>

        <?php if ($this->user->hasProjectAccess('ProjectController', 'edit', $project['id'])): ?>
            <li <?= $this->app->checkMenuSelection('ProjectController') ?>>
                <i class="fa fa-edit"></i><?= $this->url->link(t('Edit project'), 'ProjectController', 'edit', ['project_id' => $project['id']]) ?>
            </li>
            <?php if ($project['is_private'] == 0): ?>
            <li <?= $this->app->checkMenuSelection('ProjectPermissionController') ?>>
                <i class="fa fa-user"></i><?= $this->url->link(t('Project members'), 'ProjectPermissionController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->checkMenuSelection('ProjectRoleController') ?>>
                <i class="fa fa-user-plus"></i><?= $this->url->link(t('Custom roles'), 'ProjectRoleController', 'show', ['project_id' => $project['id']]) ?>
            </li>
            <?php endif ?>
            </li>
            <li <?= $this->app->checkMenuSelection('ColumnController') ?>>
                <i class="fa fa-columns"></i><?= $this->url->link(t('Columns'), 'ColumnController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->checkMenuSelection('SwimlaneController') ?>>
                <i class="fa fa-map-signs"></i><?= $this->url->link(t('Swimlanes'), 'SwimlaneController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->checkMenuSelection('CategoryController') ?>>
                <i class="fa fa-sitemap"></i><?= $this->url->link(t('Categories'), 'CategoryController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->checkMenuSelection('ProjectTagController') ?>>
                <i class="fa fa-tag"></i><?= $this->url->link(t('Tags'), 'ProjectTagController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <?php if ($this->user->hasProjectAccess('CustomFilterController', 'index', $project['id'])): ?>
            <li <?= $this->app->checkMenuSelection('CustomFilterController') ?>>
                <i class="fa fa-filter"></i><?= $this->url->link(t('Custom filters'), 'CustomFilterController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <?php endif ?>
            <li <?= $this->app->checkMenuSelection('ProjectSettingsController', 'share') ?>>
                <i class="fa fa-external-link"></i><?= $this->url->link(t('Public access'), 'ProjectSettingsController', 'share', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->checkMenuSelection('ProjectSettingsController', 'notifications') ?>>
                <i class="fa fa-bell"></i><?= $this->url->link(t('Notifications'), 'ProjectSettingsController', 'notifications', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->checkMenuSelection('ProjectSettingsController', 'integrations') ?>>
                <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'ProjectSettingsController', 'integrations', ['project_id' => $project['id']]) ?>
            <li <?= $this->app->checkMenuSelection('ActionController') ?>>
                <i class="fa fa-retweet"></i><?= $this->url->link(t('Automatic actions'), 'ActionController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->checkMenuSelection('ProjectSettingsController', 'duplicate') ?>>
                <i class="fa fa-clone"></i><?= $this->url->link(t('Duplicate'), 'ProjectSettingsController', 'duplicate', ['project_id' => $project['id']]) ?>
            </li>
                <?php if ($project['is_active']): ?>
                    <li>
                    <i class="fa fa-minus-circle"></i><?= $this->url->link(t('Disable'), 'ProjectStatusController', 'confirmDisable', ['project_id' => $project['id']], false, 'popover') ?>
                <?php else: ?>
                    <li>
                    <i class="fa fa-check-circle"></i><?= $this->url->link(t('Enable'), 'ProjectStatusController', 'confirmEnable', ['project_id' => $project['id']], false, 'popover') ?>
                <?php endif ?>
            </li>
            <?php if ($this->user->hasProjectAccess('ProjectStatusController', 'remove', $project['id'])): ?>
                <li>
                    <i class="fa fa-trash"></i><?= $this->url->link(t('Remove'), 'ProjectStatusController', 'confirmRemove', ['project_id' => $project['id']], false, 'popover') ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?= $this->hook->render('template:project:sidebar', ['project' => $project]) ?>
    </ul>
</div>
