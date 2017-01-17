<div class="subside subside-icons">
    <?php if ($this->user->hasProjectAccess('Task/TaskController', 'edit', $task['project_id'])): ?>
    <ul>
        <li>
            <i class="fa fa-pencil-square-o fa-fw"></i>
            <?= $this->url->link(t('Edit the task'), 'Task/TaskController', 'edit', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover large') ?>
        </li>
        <li>
            <i class="fa fa-refresh fa-rotate-90 fa-fw"></i>
            <?= $this->url->link(t('Edit recurrence'), 'Task/TaskRecurrenceController', 'edit', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-plus fa-fw"></i>
            <?= $this->url->link(t('Add a sub-task'), 'Task/Subtask/SubtaskController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-code-fork fa-fw"></i>
            <?= $this->url->link(t('Add internal link'), 'Task/TaskInternalLinkController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-external-link fa-fw"></i>
            <?= $this->url->link(t('Add external link'), 'Task/TaskExternalLinkController', 'find', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-comment-o fa-fw"></i>
            <?= $this->url->link(t('Add a comment'), 'Task/CommentController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-file fa-fw"></i>
            <?= $this->url->link(t('Attach a document'), 'Task/TaskFileController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-camera fa-fw"></i>
            <?= $this->url->link(t('Add a screenshot'), 'Task/TaskFileController', 'screenshot', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-files-o fa-fw"></i>
            <?= $this->url->link(t('Duplicate'), 'Task/TaskDuplicationController', 'duplicate', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-clipboard fa-fw"></i>
            <?= $this->url->link(t('Duplicate to another project'), 'Task/TaskDuplicationController', 'copy', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-clone fa-fw"></i>
            <?= $this->url->link(t('Move to another project'), 'Task/TaskDuplicationController', 'move', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <?php if ($task['is_active'] == 1 && $this->projectRole->isSortableColumn($task['project_id'], $task['column_id'])): ?>
            <li>
                <i class="fa fa-arrows fa-fw"></i>
                <?= $this->url->link(t('Move position'), 'Task/TaskMovePositionController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
            </li>
        <?php endif ?>
        <?php if ($this->projectRole->canChangeTaskStatusInColumn($task['project_id'], $task['column_id'])): ?>
            <?php if ($task['is_active'] == 1): ?>
                <li>
                    <i class="fa fa-times fa-fw"></i>
                    <?= $this->url->link(t('Close this task'), 'Task/TaskStatusController', 'close', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
                </li>
            <?php else: ?>
                <li>
                    <i class="fa fa-check-square-o fa-fw"></i>
                    <?= $this->url->link(t('Open this task'), 'Task/TaskStatusController', 'open', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
                </li>
            <?php endif ?>
        <?php endif ?>
        <?php if ($this->projectRole->canRemoveTask($task)): ?>
            <li>
                <i class="fa fa-trash-o fa-fw"></i>
                <?= $this->url->link(t('Remove'), 'Task/TaskSuppressionController', 'remove', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'redirect' => 'board'], false, 'popover') ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:task:subside:actions', ['task' => $task]) ?>
    </ul>
    <?php endif ?>
</div>