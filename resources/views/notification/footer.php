<hr/>
Jitamin

<?php if (isset($application_url) && !empty($application_url)): ?>
    - <?= $this->url->absoluteLink(t('view the task on Jitamin'), 'TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    - <?= $this->url->absoluteLink(t('view the board on Jitamin'), 'BoardController', 'show', ['project_id' => $task['project_id']]) ?>
<?php endif ?>
