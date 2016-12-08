<hr/>
Hiject

<?php if (isset($application_url) && !empty($application_url)): ?>
    - <?= $this->url->absoluteLink(t('view the task on Hiject'), 'TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    - <?= $this->url->absoluteLink(t('view the board on Hiject'), 'BoardController', 'show', ['project_id' => $task['project_id']]) ?>
<?php endif ?>
