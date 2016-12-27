<div class="page-header">
    <h2><?= t('Open a task') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to open this task: "%s"?', $task['title']) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'TaskStatusController', 'open', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'confirmation' => 'yes'], true, 'btn btn-danger popover-link') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</div>
