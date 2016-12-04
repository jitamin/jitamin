<div class="sidebar sidebar-icons">
    <div class="sidebar-title">
        <h2><?= t('Task #%d', $task['id']) ?></h2>
    </div>
    <ul>
        <li <?= $this->app->checkMenuSelection('TaskViewController', 'show') ?>>
            <i class="fa fa-newspaper-o fa-fw"></i>
            <?= $this->url->link(t('Summary'), 'TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('ActivityController', 'task') ?>>
            <i class="fa fa-history fa-fw"></i>
            <?= $this->url->link(t('Activity stream'), 'ActivityController', 'task', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('TaskViewController', 'transitions') ?>>
            <i class="fa fa-arrows-h fa-fw"></i>
            <?= $this->url->link(t('Transitions'), 'TaskViewController', 'transitions', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('TaskViewController', 'analytics') ?>>
            <i class="fa fa-bar-chart fa-fw"></i>
            <?= $this->url->link(t('Analytics'), 'TaskViewController', 'analytics', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
        </li>
        <?php if ($task['time_estimated'] > 0 || $task['time_spent'] > 0): ?>
        <li <?= $this->app->checkMenuSelection('TaskViewController', 'timetracking') ?>>
            <i class="fa fa-clock-o fa-fw"></i>
            <?= $this->url->link(t('Time tracking'), 'TaskViewController', 'timetracking', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
        </li>
        <?php endif ?>

        <?= $this->hook->render('template:task:sidebar:information', ['task' => $task]) ?>
    </ul>

    <?php if ($this->user->hasProjectAccess('TaskController', 'edit', $task['project_id'])): ?>
    <div class="sidebar-title">
        <h2><?= t('Actions') ?></h2>
    </div>
    <ul>
        <li>
            <i class="fa fa-pencil-square-o fa-fw"></i>
            <?= $this->url->link(t('Edit the task'), 'TaskController', 'edit', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-refresh fa-rotate-90 fa-fw"></i>
            <?= $this->url->link(t('Edit recurrence'), 'TaskRecurrenceController', 'edit', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-plus fa-fw"></i>
            <?= $this->url->link(t('Add a sub-task'), 'SubtaskController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-code-fork fa-fw"></i>
            <?= $this->url->link(t('Add internal link'), 'TaskInternalLinkController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-external-link fa-fw"></i>
            <?= $this->url->link(t('Add external link'), 'TaskExternalLinkController', 'find', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-comment-o fa-fw"></i>
            <?= $this->url->link(t('Add a comment'), 'CommentController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-file fa-fw"></i>
            <?= $this->url->link(t('Attach a document'), 'TaskFileController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-camera fa-fw"></i>
            <?= $this->url->link(t('Add a screenshot'), 'TaskFileController', 'screenshot', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-files-o fa-fw"></i>
            <?= $this->url->link(t('Duplicate'), 'TaskDuplicationController', 'duplicate', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-clipboard fa-fw"></i>
            <?= $this->url->link(t('Duplicate to another project'), 'TaskDuplicationController', 'copy', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-clone fa-fw"></i>
            <?= $this->url->link(t('Move to another project'), 'TaskDuplicationController', 'move', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
        </li>
        <?php if ($task['is_active'] == 1 && $this->projectRole->isSortableColumn($task['project_id'], $task['column_id'])): ?>
            <li>
                <i class="fa fa-arrows fa-fw"></i>
                <?= $this->url->link(t('Move position'), 'TaskMovePositionController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
            </li>
        <?php endif ?>
        <?php if ($this->projectRole->canChangeTaskStatusInColumn($task['project_id'], $task['column_id'])): ?>
            <?php if ($task['is_active'] == 1): ?>
                <li>
                    <i class="fa fa-times fa-fw"></i>
                    <?= $this->url->link(t('Close this task'), 'TaskStatusController', 'close', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
                </li>
            <?php else: ?>
                <li>
                    <i class="fa fa-check-square-o fa-fw"></i>
                    <?= $this->url->link(t('Open this task'), 'TaskStatusController', 'open', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'popover') ?>
                </li>
            <?php endif ?>
        <?php endif ?>
        <?php if ($this->projectRole->canRemoveTask($task)): ?>
            <li>
                <i class="fa fa-trash-o fa-fw"></i>
                <?= $this->url->link(t('Remove'), 'TaskSuppressionController', 'confirm', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'redirect' => 'board'], false, 'popover') ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:task:sidebar:actions', ['task' => $task]) ?>
    </ul>
    <?php endif ?>
</div>