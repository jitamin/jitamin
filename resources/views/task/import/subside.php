<div class="subside">
    <ul>
        <li <?= $this->app->setActive('Project/ImportController', 'show') ?>>
            <?= $this->url->link(t('Tasks').' (CSV)', 'Project/ImportController', 'show', ['project_id' => $project['id']]) ?>
        </li>
        <?= $this->hook->render('template:task-import:subside') ?>
    </ul>
</div>
