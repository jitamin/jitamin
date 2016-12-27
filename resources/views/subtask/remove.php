<div class="page-header">
    <h2><?= t('Remove a sub-task') ?></h2>
</div>

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
        <?= $this->url->link(t('Confirm'), 'SubtaskController', 'remove', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'subtask_id' => $subtask['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</div>
