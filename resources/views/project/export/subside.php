<div class="subside">
    <ul>
        <li <?= $this->app->setActive('Project/ExportController', 'tasks') ?>>
            <?= $this->url->link(t('Tasks'), 'Project/ExportController', 'tasks', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/ExportController', 'subtasks') ?>>
            <?= $this->url->link(t('Subtasks'), 'Project/ExportController', 'subtasks', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/ExportController', 'transitions') ?>>
            <?= $this->url->link(t('Task transitions'), 'Project/ExportController', 'transitions', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/ExportController', 'summary') ?>>
            <?= $this->url->link(t('Daily project summary'), 'Project/ExportController', 'summary', ['project_id' => $project['id']]) ?>
        </li>
        <?= $this->hook->render('template:export:subside') ?>
    </ul>
</div>
