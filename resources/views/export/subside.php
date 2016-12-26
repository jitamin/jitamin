<div class="subside">
    <ul>
        <li <?= $this->app->setActive('ExportController', 'tasks') ?>>
            <?= $this->url->link(t('Tasks'), 'ExportController', 'tasks', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('ExportController', 'subtasks') ?>>
            <?= $this->url->link(t('Subtasks'), 'ExportController', 'subtasks', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('ExportController', 'transitions') ?>>
            <?= $this->url->link(t('Task transitions'), 'ExportController', 'transitions', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('ExportController', 'summary') ?>>
            <?= $this->url->link(t('Daily project summary'), 'ExportController', 'summary', ['project_id' => $project['id']]) ?>
        </li>
        <?= $this->hook->render('template:export:subside') ?>
    </ul>
</div>
