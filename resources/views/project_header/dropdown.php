<div class="dropdown">
    <a href="#" class="dropdown-menu action-menu"><i class="fa fa-ellipsis-v"></i> <?= t('Menu') ?> <i class="fa fa-caret-down"></i></a>
    <ul>
        <?php if ($board_view): ?>
        <li>
            <span class="filter-display-mode" <?= $this->board->isCollapsed($project['id']) ? '' : 'style="display: none;"' ?>>
                <i class="fa fa-expand"></i>
                <?= $this->url->link(t('Expand tasks'), 'BoardAjaxController', 'expand', ['project_id' => $project['id']], false, 'board-display-mode', t('Keyboard shortcut: "%s"', 's')) ?>
            </span>
            <span class="filter-display-mode" <?= $this->board->isCollapsed($project['id']) ? 'style="display: none;"' : '' ?>>
                <i class="fa fa-compress"></i>
                <?= $this->url->link(t('Collapse tasks'), 'BoardAjaxController', 'collapse', ['project_id' => $project['id']], false, 'board-display-mode', t('Keyboard shortcut: "%s"', 's')) ?>
            </span>
        </li>
        <li>
            <span class="filter-compact">
                <i class="fa fa-columns"></i> <a href="#" class="filter-toggle-scrolling" title="<?= t('Keyboard shortcut: "%s"', 'c') ?>"><?= t('Compact view') ?></a>
            </span>
            <span class="filter-wide" style="display: none">
                <i class="fa fa-arrows-h"></i> <a href="#" class="filter-toggle-scrolling" title="<?= t('Keyboard shortcut: "%s"', 'c') ?>"><?= t('Horizontal scrolling') ?></a>
            </span>
        </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('TaskController', 'create', $project['id'])): ?>
            <li>
                <i class="fa fa-plus"></i>
                <?= $this->url->link(t('Add a new task'), 'TaskController', 'create', ['project_id' => $project['id']], false, 'popover') ?>
            </li>
        <?php endif ?>

        <li>
            <i class="fa fa-history"></i>
            <?= $this->url->link(t('Activity'), 'ActivityController', 'project', ['project_id' => $project['id']]) ?>
        </li>

        <?php if ($this->user->isStargazer($project['id'], $this->user->getId())): ?>
        <li>
            <i class="fa fa-star-o"></i>
            <?= $this->url->link(t('Unstar'), 'ProjectController', 'confirmUnstar', ['project_id' => $project['id']], true, 'popover') ?>
        </li>
        <?php else: ?>
        <li>
            <i class="fa fa-star"></i>
            <?= $this->url->link(t('Star'), 'ProjectController', 'confirmStar', ['project_id' => $project['id']], true, 'popover') ?>
        </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('CustomFilterController', 'index', $project['id'])): ?>
            <li>
                <i class="fa fa-filter"></i>
                <?= $this->url->link(t('Custom filters'), 'CustomFilterController', 'index', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($project['is_public']): ?>
            <li>
                <i class="fa fa-share-alt"></i>
                <?= $this->url->link(t('Public link'), 'BoardController', 'readonly', ['token' => $project['token']], false, '', '', true) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:project:dropdown', ['project' => $project]) ?>

        <?php if ($this->user->hasProjectAccess('AnalyticController', 'taskDistribution', $project['id'])): ?>
            <li>
                <i class="fa fa-line-chart"></i>
                <?= $this->url->link(t('Analytics'), 'AnalyticController', 'taskDistribution', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('ExportController', 'tasks', $project['id'])): ?>
            <li>
                <i class="fa fa-upload"></i>
                <?= $this->url->link(t('Exports'), 'ExportController', 'tasks', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('TaskImportController', 'tasks', $project['id'])): ?>
            <li>
                <i class="fa fa-download"></i>
                <?= $this->url->link(t('Imports'), 'TaskImportController', 'show', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('ProjectController', 'edit', $project['id'])): ?>
            <li>
                <i class="fa fa-cog"></i>
                <?= $this->url->link(t('Settings'), 'ProjectSettingsController', 'show', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>
    </ul>
</div>
