<div class="subside subside-icons">
    <ul>
        <li <?= $this->app->setActive('Project/ProjectSettingsController', 'show') ?>>
            <i class="fa fa-eye"></i><?= $this->url->link(t('Summary'), 'Project/ProjectSettingsController', 'show', ['project_id' => $project['id']]) ?>
        </li>

        <?php if ($this->user->hasProjectAccess('Project/ProjectController', 'edit', $project['id'])): ?>
            <li <?= $this->app->setActive('Project/ProjectController') ?>>
                <i class="fa fa-edit"></i><?= $this->url->link(t('Edit project'), 'Project/ProjectController', 'edit', ['project_id' => $project['id']]) ?>
            </li>
            <?php if ($project['is_private'] == 0): ?>
            <li <?= $this->app->setActive('Project/ProjectPermissionController') ?>>
                <i class="fa fa-user"></i><?= $this->url->link(t('Project members'), 'Project/ProjectPermissionController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/ProjectRoleController') ?>>
                <i class="fa fa-user-plus"></i><?= $this->url->link(t('Custom roles'), 'Project/ProjectRoleController', 'show', ['project_id' => $project['id']]) ?>
            </li>
            <?php endif ?>
            </li>
            <li <?= $this->app->setActive('ColumnController') ?>>
                <i class="fa fa-columns"></i><?= $this->url->link(t('Columns'), 'ColumnController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('SwimlaneController') ?>>
                <i class="fa fa-map-signs"></i><?= $this->url->link(t('Swimlanes'), 'SwimlaneController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('CategoryController') ?>>
                <i class="fa fa-sitemap"></i><?= $this->url->link(t('Categories'), 'CategoryController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/ProjectTagController') ?>>
                <i class="fa fa-tag"></i><?= $this->url->link(t('Tags'), 'Project/ProjectTagController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <?php if ($this->user->hasProjectAccess('CustomFilterController', 'index', $project['id'])): ?>
            <li <?= $this->app->setActive('CustomFilterController') ?>>
                <i class="fa fa-filter"></i><?= $this->url->link(t('Custom filters'), 'CustomFilterController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <?php endif ?>
            <li <?= $this->app->setActive('Project/ProjectSettingsController', 'share') ?>>
                <i class="fa fa-external-link"></i><?= $this->url->link(t('Public access'), 'Project/ProjectSettingsController', 'share', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/ProjectSettingsController', 'notifications') ?>>
                <i class="fa fa-bell"></i><?= $this->url->link(t('Notifications'), 'Project/ProjectSettingsController', 'notifications', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/ProjectSettingsController', 'integrations') ?>>
                <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'Project/ProjectSettingsController', 'integrations', ['project_id' => $project['id']]) ?>
            <li <?= $this->app->setActive('ActionController') ?>>
                <i class="fa fa-retweet"></i><?= $this->url->link(t('Automatic actions'), 'ActionController', 'index', ['project_id' => $project['id']]) ?>
            </li>
            <li <?= $this->app->setActive('Project/ProjectSettingsController', 'duplicate') ?>>
                <i class="fa fa-clone"></i><?= $this->url->link(t('Duplicate'), 'Project/ProjectSettingsController', 'duplicate', ['project_id' => $project['id']]) ?>
            </li>
                <?php if ($project['is_active']): ?>
                    <li>
                    <i class="fa fa-minus-circle"></i><?= $this->url->link(t('Disable'), 'Project/ProjectStatusController', 'confirmDisable', ['project_id' => $project['id']], false, 'popover') ?>
                <?php else: ?>
                    <li>
                    <i class="fa fa-check-circle"></i><?= $this->url->link(t('Enable'), 'Project/ProjectStatusController', 'confirmEnable', ['project_id' => $project['id']], false, 'popover') ?>
                <?php endif ?>
            </li>
            <?php if ($this->user->hasProjectAccess('Project/ProjectStatusController', 'remove', $project['id'])): ?>
                <li>
                    <i class="fa fa-trash"></i><?= $this->url->link(t('Remove'), 'Project/ProjectStatusController', 'confirmRemove', ['project_id' => $project['id']], false, 'popover') ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?= $this->hook->render('template:project:subside', ['project' => $project]) ?>
    </ul>
</div>
