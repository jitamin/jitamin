<?= $this->hook->render('template:task:show:top', ['task' => $task, 'project' => $project]) ?>

<?= $this->render('task/details', [
    'task'     => $task,
    'tags'     => $tags,
    'project'  => $project,
    'editable' => $this->user->hasProjectAccess('Task/TaskController', 'edit', $project['id']),
]) ?>

<?php if (!empty($task['description'])): ?>
    <?= $this->hook->render('template:task:show:before-description', ['task' => $task, 'project' => $project]) ?>
    <?= $this->render('task/description', ['task' => $task]) ?>
<?php endif ?>

<?php if (!empty($subtasks)): ?>
    <?= $this->hook->render('template:task:show:before-subtasks', ['task' => $task, 'project' => $project]) ?>
    <?= $this->render('subtask/show', [
        'task'     => $task,
        'subtasks' => $subtasks,
        'project'  => $project,
        'editable' => true,
    ]) ?>
<?php endif ?>

<?php if (!empty($internal_links)): ?>
    <?= $this->hook->render('template:task:show:before-internal-links', ['task' => $task, 'project' => $project]) ?>
    <?= $this->render('task_internal_link/show', [
        'task'            => $task,
        'links'           => $internal_links,
        'project'         => $project,
        'link_label_list' => $link_label_list,
        'editable'        => true,
        'is_public'       => false,
    ]) ?>
<?php endif ?>

<?php if (!empty($external_links)): ?>
    <?= $this->hook->render('template:task:show:before-external-links', ['task' => $task, 'project' => $project]) ?>
    <?= $this->render('task_external_link/show', [
        'task'    => $task,
        'links'   => $external_links,
        'project' => $project,
    ]) ?>
<?php endif ?>

<?php if (!empty($files) || !empty($images)): ?>
    <?= $this->hook->render('template:task:show:before-attachments', ['task' => $task, 'project' => $project]) ?>
    <?= $this->render('task/attachment/show', [
        'task'   => $task,
        'files'  => $files,
        'images' => $images,
    ]) ?>
<?php endif ?>

<?php if (!empty($comments)): ?>
    <?= $this->hook->render('template:task:show:before-comments', ['task' => $task, 'project' => $project]) ?>
    <?= $this->render('comments/show', [
        'task'     => $task,
        'comments' => $comments,
        'project'  => $project,
        'editable' => $this->user->hasProjectAccess('CommentController', 'edit', $project['id']),
    ]) ?>
<?php endif ?>

<?= $this->hook->render('template:task:show:bottom', ['task' => $task, 'project' => $project]) ?>
