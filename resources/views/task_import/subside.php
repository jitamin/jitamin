<div class="subside">
    <ul>
        <li <?= $this->app->setActive('Task/TaskImportController', 'show') ?>>
            <?= $this->url->link(t('Tasks').' (CSV)', 'Task/TaskImportController', 'show', ['project_id' => $project['id']]) ?>
        </li>
        <?= $this->hook->render('template:task-import:subside') ?>
    </ul>
</div>
