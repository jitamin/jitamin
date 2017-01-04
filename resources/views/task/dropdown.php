<div class="dropdown">
    <a href="#" class="dropdown-menu">#<?= $task['id'] ?> <i class="fa fa-caret-down"></i></a>
    <ul>
        <?php if (array_key_exists('date_started', $task) && empty($task['date_started'])): ?>
        <li>
            <i class="fa fa-play fa-fw"></i>
            <?= $this->url->link(t('Set automatically the start date'), 'Task/TaskController', 'start', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
        </li>
        <?php endif ?>
        <li>
            <i class="fa fa-pencil-square-o fa-fw"></i>
            <?= $this->url->link(t('Edit the task'), 'Task/TaskController', 'edit', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-plus fa-fw"></i>
            <?= $this->url->link(t('Add a sub-task'), 'Task/Subtask/SubtaskController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover medium') ?>
        </li>
        <li>
            <i class="fa fa-code-fork fa-fw"></i>
            <?= $this->url->link(t('Add internal link'), 'Task/TaskInternalLinkController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-comment-o fa-fw"></i>
            <?= $this->url->link(t('Add a comment'), 'CommentController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover small') ?>
        </li>
        <li>
            <i class="fa fa-camera fa-fw"></i>
            <?= $this->url->link(t('Add a screenshot'), 'Task/TaskPopoverController', 'screenshot', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <?php if ($this->projectRole->canRemoveTask($task)): ?>
            <li>
                <i class="fa fa-trash-o fa-fw"></i>
                <?= $this->url->link(t('Remove'), 'Task/TaskSuppressionController', 'confirm', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
            </li>
        <?php endif ?>
        <?php if (isset($task['is_active']) && $this->projectRole->canChangeTaskStatusInColumn($task['project_id'], $task['column_id'])): ?>
        <li>
            <?php if ($task['is_active'] == 1): ?>
                <i class="fa fa-times fa-fw"></i>
                <?= $this->url->link(t('Close this task'), 'Task/TaskStatusController', 'close', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
            <?php else: ?>
                <i class="fa fa-check-square-o fa-fw"></i>
                <?= $this->url->link(t('Open this task'), 'Task/TaskStatusController', 'open', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
            <?php endif ?>
        </li>
        <?php endif ?>

        <?= $this->hook->render('template:task:dropdown', ['task' => $task]) ?>
    </ul>
</div>
