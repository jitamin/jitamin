<div class="page-header">
    <h2><?= t('Convert sub-task to task') ?></h2>
</div>

<div class="confirm">
    <div class="alert alert-info">
        <?= t('Do you really want to convert this sub-task to a task?') ?>
        <ul>
            <li>
                <strong><?= $this->text->e($subtask['title']) ?></strong>
            </li>
        </ul>
    </div>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'SubtaskConverterController', 'store', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'subtask_id' => $subtask['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</div>
