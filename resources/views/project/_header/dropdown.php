<div class="dropdown">
    <a href="#" class="dropdown-menu action-menu"><i class="fa fa-ellipsis-v"></i> <?= t('Menu') ?> <i class="fa fa-caret-down"></i></a>
    <ul>
        <?php if ($board_view): ?>
        <li>
            <span class="filter-display-mode" <?= $this->board->isCollapsed($project['id']) ? '' : 'style="display: none;"' ?>>
                <i class="fa fa-expand"></i>
                <?= $this->url->link(t('Expand tasks'), 'Project/Board/BoardAjaxController', 'expand', ['project_id' => $project['id']], false, 'board-display-mode', t('Keyboard shortcut: "%s"', 's')) ?>
            </span>
            <span class="filter-display-mode" <?= $this->board->isCollapsed($project['id']) ? 'style="display: none;"' : '' ?>>
                <i class="fa fa-compress"></i>
                <?= $this->url->link(t('Collapse tasks'), 'Project/Board/BoardAjaxController', 'collapse', ['project_id' => $project['id']], false, 'board-display-mode', t('Keyboard shortcut: "%s"', 's')) ?>
            </span>
        </li>
        <li>
            <span class="filter-compact">
                <i class="fa fa-columns"></i> <a href="#" class="filter-toggle-scrolling" title="<?= t('Keyboard shortcut: "%s"', 'c') ?>"><?= t('Compact view') ?></a>
            </span>
            <span class="filter-wide" style="display: none">
                <i class="fa fa-arrows-h"></i> <a href="#" class="filter-toggle-scrolling" title="<?= t('Keyboard shortcut: "%s"', 'c') ?>"><?= t('Wide view') ?></a>
            </span>
        </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('Task/TaskController', 'create', $project['id'])): ?>
            <li>
                <i class="fa fa-plus"></i>
                <?= $this->url->link(t('Add a new task'), 'Task/TaskController', 'create', ['project_id' => $project['id']], false, 'popover large') ?>
            </li>
        <?php endif ?>

        <li>
            <i class="fa fa-history"></i>
            <?= $this->url->link(t('Activity'), 'ActivityController', 'project', ['project_id' => $project['id']]) ?>
        </li>

        <?php if ($this->user->isStargazer($project['id'], $this->user->getId())): ?>
        <li>
            <i class="fa fa-star-o"></i>
            <?= $this->url->link(t('Unstar'), 'Project/ProjectController', 'unstar', ['project_id' => $project['id']], false, 'popover') ?>
        </li>
        <?php else: ?>
        <li>
            <i class="fa fa-star"></i>
            <?= $this->url->link(t('Star'), 'Project/ProjectController', 'star', ['project_id' => $project['id']], false, 'popover') ?>
        </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('Project/CustomFilterController', 'index', $project['id'])): ?>
            <li>
                <i class="fa fa-filter"></i>
                <?= $this->url->link(t('Custom filters'), 'Project/CustomFilterController', 'index', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($project['is_public']): ?>
            <li>
                <i class="fa fa-share-alt"></i>
                <?= $this->url->link(t('Public link'), 'Project/Board/BoardController', 'readonly', ['token' => $project['token']], false, '', '', true) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:project:dropdown', ['project' => $project]) ?>

        <?php if ($this->user->hasProjectAccess('Project/AnalyticController', 'taskDistribution', $project['id'])): ?>
            <li>
                <i class="fa fa-line-chart"></i>
                <?= $this->url->link(t('Analytics'), 'Project/AnalyticController', 'taskDistribution', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('Project/ExportController', 'tasks', $project['id'])): ?>
            <li>
                <i class="fa fa-upload"></i>
                <?= $this->url->link(t('Exports'), 'Project/ExportController', 'tasks', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('Project/ImportController', 'tasks', $project['id'])): ?>
            <li>
                <i class="fa fa-download"></i>
                <?= $this->url->link(t('Imports'), 'Project/ImportController', 'show', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?php if ($this->user->hasProjectAccess('Manage/ProjectSettingsController', 'edit', $project['id'])): ?>
            <li>
                <i class="fa fa-cog"></i>
                <?= $this->url->link(t('Settings'), 'Manage/ProjectSettingsController', 'edit', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>
    </ul>
</div>
