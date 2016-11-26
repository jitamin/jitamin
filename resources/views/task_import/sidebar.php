<div class="sidebar">
    <ul>
        <li <?= $this->app->checkMenuSelection('TaskImportController', 'show') ?>>
            <?= $this->url->link(t('Tasks').' (CSV)', 'TaskImportController', 'show', ['project_id' => $project['id']]) ?>
        </li>
        <?= $this->hook->render('template:task-import:sidebar') ?>
    </ul>
</div>
