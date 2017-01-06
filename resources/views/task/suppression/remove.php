<div class="page-header">
    <h2><?= t('Remove a task') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove this task: "%s"?', $this->text->e($task['title'])) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'Task/TaskSuppressionController', 'remove', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'redirect' => $redirect], true, 'btn btn-danger popover-link') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</div>
