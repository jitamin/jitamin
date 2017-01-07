<div class="page-header">
    <h2><?= t('Remove a task') ?></h2>
</div>

<form action="<?= $this->url->href('Task/TaskSuppressionController', 'remove', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'redirect' => $redirect]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this task: "%s"?', $this->text->e($task['title'])) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
