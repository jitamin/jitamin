<div class="page-header">
    <h2><?= t('Remove a sub-task') ?></h2>
</div>

<form action="<?= $this->url->href('Task/Subtask/SubtaskController', 'remove', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'subtask_id' => $subtask['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <div class="alert alert-info">
            <?= t('Do you really want to remove this sub-task?') ?>
            <ul>
                <li>
                    <strong><?= $this->text->e($subtask['title']) ?></strong>
                </li>
            </ul>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
