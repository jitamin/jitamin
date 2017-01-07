<div class="subside subside-icons">
    <ul>
        <?php if ($this->user->hasProjectAccess('Manage/ProjectSettingsController', 'edit', $project['id'])): ?>
            <li <?= $this->app->setActive('Manage/ProjectSettingsController') ?>>
                <i class="fa fa-edit"></i><?= $this->url->link(t('Edit project'), 'Manage/ProjectSettingsController', 'edit', ['project_id' => $project['id']]) ?>
            </li>
            <?php if ($project['is_private'] == 0): ?>
            <li <?= $this->app->setActive('Manage/ProjectPermissionController') ?>>
                <i class="fa fa-user"></i><?= $this->url->link(t('Project members'), 'Manage/ProjectPermissionController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/ProjectRoleController') ?>>
                <i class="fa fa-user-plus"></i><?= $this->url->link(t('Custom roles'), 'Project/ProjectRoleController', 'show', ['project_id' => $project['id']]) ?>
            </li>
            <?php endif ?>
            </li>
            <li <?= $this->app->setActive('Project/Column/ColumnController') ?>>
                <i class="fa fa-columns"></i><?= $this->url->link(t('Columns'), 'Project/Column/ColumnController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/SwimlaneController') ?>>
                <i class="fa fa-map-signs"></i><?= $this->url->link(t('Swimlanes'), 'Project/SwimlaneController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/CategoryController') ?>>
                <i class="fa fa-sitemap"></i><?= $this->url->link(t('Categories'), 'Project/CategoryController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Manage/ProjectTagController') ?>>
                <i class="fa fa-tag"></i><?= $this->url->link(t('Tags'), 'Manage/ProjectTagController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <?php if ($this->user->hasProjectAccess('Project/CustomFilterController', 'index', $project['id'])): ?>
            <li <?= $this->app->setActive('Project/CustomFilterController') ?>>
                <i class="fa fa-filter"></i><?= $this->url->link(t('Custom filters'), 'Project/CustomFilterController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <?php endif ?>
            <li <?= $this->app->setActive('Manage/ProjectSettingsController', 'share') ?>>
                <i class="fa fa-external-link"></i><?= $this->url->link(t('Public access'), 'Manage/ProjectSettingsController', 'share', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Manage/ProjectSettingsController', 'notifications') ?>>
                <i class="fa fa-bell"></i><?= $this->url->link(t('Notifications'), 'Manage/ProjectSettingsController', 'notifications', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Manage/ProjectSettingsController', 'integrations') ?>>
                <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'Manage/ProjectSettingsController', 'integrations', ['project_id' => $project['id']]) ?>
            <li <?= $this->app->setActive('Project/ActionController') ?>>
                <i class="fa fa-retweet"></i><?= $this->url->link(t('Automatic actions'), 'Project/ActionController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Manage/ProjectSettingsController', 'duplicate') ?>>
                <i class="fa fa-clone"></i><?= $this->url->link(t('Duplicate'), 'Manage/ProjectSettingsController', 'duplicate', ['project_id' => $project['id']]) ?>
            </li>
                <?php if ($project['is_active']): ?>
                    <li>
                    <i class="fa fa-minus-circle"></i><?= $this->url->link(t('Disable'), 'Manage/ProjectStatusController', 'disable', ['project_id' => $project['id']], false, 'popover') ?>
                <?php else: ?>
                    <li>
                    <i class="fa fa-check-circle"></i><?= $this->url->link(t('Enable'), 'Manage/ProjectStatusController', 'enable', ['project_id' => $project['id']], false, 'popover') ?>
                <?php endif ?>
            </li>
            <?php if ($this->user->hasProjectAccess('Manage/ProjectStatusController', 'remove', $project['id'])): ?>
                <li>
                    <i class="fa fa-trash"></i><?= $this->url->link(t('Remove'), 'Manage/ProjectStatusController', 'remove', ['project_id' => $project['id']], false, 'popover') ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?= $this->hook->render('template:project:subside', ['project' => $project]) ?>
    </ul>
</div>
